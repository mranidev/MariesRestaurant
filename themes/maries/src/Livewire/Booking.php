<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Carbon\Carbon;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\Local\Facades\Location;
use Igniter\Reservation\Classes\BookingManager;
use Igniter\User\Facades\Auth;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Throwable;

/**
 * Booking component.
 *
 * Wires the theme's "Reserve a Table" flow to the reservations extension
 * (BookingManager) so a submitted reservation is saved through the real
 * TastyIgniter reservation pipeline: status history, table auto-allocation
 * and the "new reservation" mail/automation events.
 *
 * Two steps, matching the reference reservation page:
 *   1. picker   – inline calendar + guests + time + "Find Table"
 *   2. details  – summary row + contact details + "Confirm Booking"
 */
class Booking extends Component
{
    public const STEP_PICKER = 'picker';

    public const STEP_DETAILS = 'details';

    public string $pickerStep = self::STEP_PICKER;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $email = null;

    public ?string $telephone = null;

    public ?string $date = null;

    public ?string $time = null;

    public ?int $guest = null;

    public ?string $comment = null;

    public bool $success = false;

    public ?string $successHash = null;

    public ?string $errorMessage = null;

    public int $minGuestSize = 1;

    public int $maxGuestSize = 20;

    public Carbon $startDate;

    public Carbon $endDate;

    protected BookingManager $manager;

    public function boot(): void
    {
        $this->manager = resolve(BookingManager::class);
        $this->manager->useLocation(Location::currentOrDefault());
    }

    public function mount(): void
    {
        $location = Location::currentOrDefault();

        $this->minGuestSize = max(1, (int)$location->getMinReservationGuestCount());
        $this->maxGuestSize = max($this->minGuestSize, (int)$location->getMaxReservationGuestCount());
        $this->startDate = now()->addDays((int)$location->getMinReservationAdvanceTime())->startOfDay();
        $this->endDate = now()->addDays((int)$location->getMaxReservationAdvanceTime())->endOfDay();
        $this->guest = $this->minGuestSize;
        $this->date = $this->startDate->format('Y-m-d');

        if ($customer = Auth::customer()) {
            $this->firstName = $customer->first_name;
            $this->lastName = $customer->last_name;
            $this->email = $customer->email;
            $this->telephone = $customer->telephone;
        }
    }

    public function render(): View
    {
        return view('maries::livewire.booking', [
            'minDate' => $this->startDate->format('Y-m-d'),
            'maxDate' => $this->endDate->format('Y-m-d'),
            'timeSlots' => $this->timeSlots(),
            'locationName' => Location::currentOrDefault()->getName(),
            'isOpen' => $this->locationIsOpen(),
        ]);
    }

    public function onFindTable(): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->withValidator(function ($validator): void {
            $validator->after(function ($validator): void {
                if (!$this->date || !$this->time) {
                    return;
                }

                $dateTime = make_carbon($this->date.' '.$this->time);

                if ($dateTime->lt($this->startDate) || $dateTime->gt($this->endDate)) {
                    $validator->errors()->add('date', sprintf(
                        'The date must be between %s and %s.',
                        $this->startDate->isoFormat('D MMMM YYYY'),
                        $this->endDate->isoFormat('D MMMM YYYY'),
                    ));
                }

                if (!$this->manager->getSchedule()->isOpenAt($dateTime)) {
                    $validator->errors()->add('time', 'Sorry, we are closed at that time. Please pick another slot.');
                }
            });
        });

        $this->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'guest' => ['required', 'integer', 'min:'.$this->minGuestSize, 'max:'.$this->maxGuestSize],
        ], [], [
            'date' => 'date',
            'time' => 'time',
            'guest' => 'number of guests',
        ]);

        $this->pickerStep = self::STEP_DETAILS;
    }

    public function backToPicker(): void
    {
        $this->resetErrorBag();
        $this->pickerStep = self::STEP_PICKER;
    }

    public function onSave(): void
    {
        $this->resetErrorBag();
        $this->success = false;
        $this->successHash = null;
        $this->errorMessage = null;

        $this->validate([
            'firstName' => ['required', 'between:1,48'],
            'lastName' => ['required', 'between:1,48'],
            'email' => ['required', 'email:filter', 'max:96'],
            'telephone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/i'],
            'comment' => ['max:500'],
        ], [], [
            'firstName' => 'first name',
            'lastName' => 'last name',
            'email' => 'email address',
            'telephone' => 'telephone number',
            'comment' => 'message',
        ]);

        try {
            $reservation = $this->manager->loadReservation();

            $data = [
                'sdateTime' => make_carbon($this->date.' '.$this->time)->format('Y-m-d H:i'),
                'guest' => $this->guest,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'comment' => $this->comment,
            ];

            $this->manager->saveReservation($reservation, $data);

            $this->success = true;
            $this->successHash = $reservation->hash;
            $this->reset('firstName', 'lastName', 'email', 'telephone', 'comment');
            $this->guest = $this->minGuestSize;
        } catch (ApplicationException $ex) {
            $this->errorMessage = $ex->getMessage();
        } catch (Throwable $ex) {
            log_message('error', 'Booking could not be saved: '.$ex->getMessage(), ['exception' => $ex]);
            $this->errorMessage = 'Sorry, we could not process your booking right now. Please try again or call us.';
        }
    }

    protected function timeSlots(): array
    {
        $slots = [];
        $carbon = Carbon::createFromTime(0, 0);

        for ($i = 0; $i < 96; $i++) {
            $value = $carbon->format('H:i');
            $slots[$value] = $carbon->format('h:i a');
            $carbon->addMinutes(15);
        }

        return $slots;
    }

    protected function locationIsOpen(): bool
    {
        try {
            return (bool)Location::currentOrDefault()->getSchedule()->isOpen();
        } catch (Throwable) {
            return true;
        }
    }
}
