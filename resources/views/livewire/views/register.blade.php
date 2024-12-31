<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.centered')] class extends Component {
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
    }

    public function register()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = \App\Models\User::create([
            'email' => $this->email,
            'name' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        auth()->login($user);

        return redirect()->route('login');
    }

}; ?>

<div>
    <div class="text-center">
        <h2 class="text-2xl font-bold dark:text-gray-100">Register</h2>
        <p class="text-sm text-gray-500 dark:text-gray-300">Create your account</p>
    </div>
    <form wire:submit.prevent="register" class="mt-6 space-y-6">
        <x-input name="email" type="email" wire:model="email" label="Email"/>
        <x-input name="password" type="password" wire:model="password" label="Password"/>
        <x-input name="password_confirmation" type="password" wire:model="password_confirmation"
                    label="Confirm Password"/>
        <div>
            <x-button type="submit" class="w-full">
                Register
            </x-button>
            <span class="text-gray-500 text-sm text-center block my-2">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-500 mt-2">Login</a>
            </span>
        </div>
    </form>
</div>
