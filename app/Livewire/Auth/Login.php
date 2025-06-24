<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    #[Title('Login - Organice')]
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
    ];

    public function login()
    {
        $credentials = $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'The provided credentials do not match our records.');

        return null;
    }

    #[On('credentials-selected')]
    public function fillCredentials($credentials)
    {
        if (isset($credentials['email']) && isset($credentials['password'])) {
            $this->email = $credentials['email'];
            $this->password = $credentials['password'];
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
