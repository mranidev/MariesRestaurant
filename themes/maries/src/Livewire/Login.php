<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\User\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

/**
 * Customer login (frontend). Authenticates through TastyIgniter's customer
 * guard so a logged-in customer's details pre-fill booking and checkout.
 */
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public ?string $errorMessage = null;

    public function render(): View
    {
        return view('maries::livewire.login', [
            'customer' => Auth::customer(),
        ]);
    }

    public function onLogin()
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'email' => ['required', 'email:filter', 'max:96'],
            'password' => ['required'],
        ], [], [
            'email' => 'email address',
            'password' => 'password',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return Redirect::intended(url('/'));
        }

        $this->errorMessage = 'These credentials do not match our records.';
    }

    public function onLogout()
    {
        Auth::logout();

        return Redirect::to(url('/'));
    }
}
