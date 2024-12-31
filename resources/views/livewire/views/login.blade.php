<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.centered')] class extends Component {

    public string $email;
    public string $password;
    public bool $remember_me = false;

    use \App\Traits\Toastable;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
    }


    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $this->sendSuccessToast('Login successful',flash: true);
            return redirect()->intended('/');
        }

        $this->addError('email', 'Invalid credentials');
    }

}; ?>


<div>
    <div class="text-center">
        <h2 class="text-2xl font-bold
            dark:text-gray-100 text-gray-900
        ">Login</h2>
        <p class="text-sm text-gray-500
            dark:text-gray-300
        ">Enter your credentials below</p>
    </div>
    <form wire:submit.prevent="login" class="mt-6 space-y-6">
        <x-input name="email" type="email" wire:model="email" label="Email"/>
        <x-input name="password" type="password" wire:model="password" label="Password"/>
        <x-checkbox name="remember_me" label="Remember me" wire:model="remember_me"/>
        <div>
            <x-button type="submit" class="w-full">
                Login
            </x-button>
            <span class="text-gray-500 text-sm text-center block my-2
                dark:text-gray-300
            ">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 mt-2">Register</a>
            </span>
        </div>
    </form>
</div>
