<div>
    @if ($success)
        <div class="text-center py-4">
            <div class="sent-message" style="display: block; font-size: 1.05rem;">
                Your booking request was sent. We will call back or send an
                Email to confirm your reservation. Thank you!
                <br />
                <small>Reservation reference: {{ $successHash }}</small>
            </div>
        </div>
    @else
        @if ($pickerStep === \Maries\Livewire\Booking::STEP_PICKER)
            <h1 class="h3 fw-bold mb-4">Reserve A Table</h1>

            <form wire:submit="onFindTable" novalidate>
                <div class="form-row align-items-center g-0">
                    <div wire:ignore class="col-md-8 pr-md-4 booking-calendar-col">
                        <input
                            wire:model="date"
                            type="text"
                            name="date"
                            class="booking-date-hidden d-none"
                            data-inline="true"
                            data-min-date="{{ $minDate }}"
                            data-max-date="{{ $maxDate }}"
                            value="{{ $date }}"
                        />
                        @error('date')
                            <div class="field-error text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0" id="ti-datepicker-options-{{ $this->getId() }}">
                        <div class="form-group mb-3">
                            <div class="form-floating">
                                <select
                                    wire:model="guest"
                                    name="guest"
                                    id="noOfGuests-{{ $this->getId() }}"
                                    class="form-select @error('guest') is-invalid @enderror"
                                >
                                    @for ($i = $minGuestSize; $i <= $maxGuestSize; $i++)
                                        <option value="{{ $i }}" @if ((int) $guest === $i) selected @endif>
                                            {{ $i }} {{ $i === 1 ? 'person' : 'people' }}
                                        </option>
                                    @endfor
                                </select>
                                <label for="noOfGuests-{{ $this->getId() }}">Number of guests</label>
                                @error('guest')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-floating">
                                <select
                                    wire:model="time"
                                    name="time"
                                    id="time-{{ $this->getId() }}"
                                    class="form-select @error('time') is-invalid @enderror"
                                >
                                    <option value="">Select a time</option>
                                    @foreach ($timeSlots as $value => $label)
                                        <option value="{{ $value }}" @if ($time === $value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <label for="time-{{ $this->getId() }}">Time</label>
                                @error('time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
                                <span wire:loading.remove wire:target="onFindTable">Find Table</span>
                                <span wire:loading wire:target="onFindTable">Checking&hellip;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <h1 class="h3 fw-bold mb-4">Confirm Booking Details</h1>

            <div class="form-row mb-4 g-0 booking-summary">
                <div class="col-6 col-sm-3 mb-2">
                    <h5 class="text-muted small text-uppercase mb-1">Guests</h5>
                    <h4 class="fw-bold mb-0">{{ $guest }} {{ $guest === 1 ? 'person' : 'people' }}</h4>
                </div>
                <div class="col-6 col-sm-3 mb-2">
                    <h5 class="text-muted small text-uppercase mb-1">Date</h5>
                    <h4 class="fw-bold mb-0">{{ make_carbon($date.' '.$time)?->isoFormat('D MMM YYYY') }}</h4>
                </div>
                <div class="col-6 col-sm-3 mb-2">
                    <h5 class="text-muted small text-uppercase mb-1">Time</h5>
                    <h4 class="fw-bold mb-0">{{ make_carbon($date.' '.$time)?->isoFormat('hh:mm A') }}</h4>
                </div>
                <div class="col-6 col-sm-3 mb-2">
                    <h5 class="text-muted small text-uppercase mb-1">Location</h5>
                    <h4 class="fw-bold mb-0">{{ $locationName }}</h4>
                </div>
            </div>

            <form wire:submit="onSave" novalidate>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input
                                wire:model="firstName"
                                type="text"
                                id="bookingFirstName"
                                class="form-control @error('firstName') is-invalid @enderror"
                                placeholder="First name"
                                autocomplete="given-name"
                            />
                            <label for="bookingFirstName">First name</label>
                            @error('firstName')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input
                                wire:model="lastName"
                                type="text"
                                id="bookingLastName"
                                class="form-control @error('lastName') is-invalid @enderror"
                                placeholder="Last name"
                                autocomplete="family-name"
                            />
                            <label for="bookingLastName">Last name</label>
                            @error('lastName')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input
                                wire:model="email"
                                type="text"
                                id="bookingEmail"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email"
                                autocomplete="email"
                            />
                            <label for="bookingEmail">Email</label>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-floating">
                            <input
                                wire:model="telephone"
                                type="text"
                                id="bookingTelephone"
                                class="form-control @error('telephone') is-invalid @enderror"
                                placeholder="Phone"
                                autocomplete="tel"
                            />
                            <label for="bookingTelephone">Phone</label>
                            @error('telephone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-floating">
                        <textarea
                            wire:model="comment"
                            id="bookingComment"
                            rows="4"
                            class="form-control @error('comment') is-invalid @enderror"
                            placeholder="Message (optional)"
                        ></textarea>
                        <label for="bookingComment">Message (optional)</label>
                        @error('comment')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($errorMessage)
                    <div class="error-message mb-3" style="display: block;">{{ $errorMessage }}</div>
                @endif

                <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill">
                    <span wire:loading.remove wire:target="onSave">Confirm Booking</span>
                    <span wire:loading wire:target="onSave">Booking&hellip;</span>
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="#" wire:click.prevent="backToPicker" class="text-muted small text-decoration-none">
                    &larr; Start again
                </a>
            </div>
        @endif
    @endif
</div>
