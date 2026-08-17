<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\User\Facades\Auth;
use Igniter\User\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Throwable;

/**
 * Customer registration (frontend). Creates a customer through the real
 * TastyIgniter customer model (password auto-hashed via the model cast) and
 * logs them straight in.
 */
class Register extends Component
{
    public string $firstName = '';

    public string $lastName = '';

    public string $email = '';

    public string $telephone = '';

    public string $password = '';

    public ?string $errorMessage = null;

    public function render(): View
    {
        return view('maries::livewire.register', [
            'customer' => Auth::customer(),
        ]);
    }

    public function onRegister()
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'firstName' => ['required', 'between:1,48'],
            'lastName' => ['required', 'between:1,48'],
            'email' => ['required', 'email:filter', 'max:96', 'unique:'.Customer::class.',email'],
            'telephone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/i'],
            'password' => ['required', 'min:6', 'max:64'],
        ], [], [
            'firstName' => 'first name',
            'lastName' => 'last name',
            'email' => 'email address',
            'telephone' => 'telephone number',
            'password' => 'password',
        ]);

        try {
            $customer = Customer::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'password' => $this->password,
                'status' => true,
            ]);

            Auth::login($customer);

            return Redirect::intended(url('/'));
        } catch (Throwable $ex) {
            log_message('error', 'Customer registration failed: '.$ex->getMessage(), ['exception' => $ex]);
            $this->errorMessage = 'Sorry, we could not create your account right now. Please try again or call us.';
        }
    }
}
