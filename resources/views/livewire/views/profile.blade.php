<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use WithFileUploads;
    use Interactions;

    public $name;
    public $email;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public $avatar;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;

    }

    public function updateInformations()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);


        //refresh the page
//        $this->redirect(route('profile'));

    }

    public function changeAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:1024'
        ]);

        $path = $this->avatar->store('avatars');

        auth()->user()->update([
            'avatar' => $path
        ]);

        $this->toast()->success('Avatar successfully updated')->send();

    }

    public function confirmAvatarReset()
    {
        $this->dialog()
            ->question('Warning!', 'Are you sure?')
            ->confirm('Confirm', 'resetAvatar')
            ->cancel('Cancel')
            ->send();
    }

    public function resetAvatar()
    {
        auth()->user()->update([
            'avatar' => null
        ]);

        $this->toast()->success('Avatar successfully reset')->send();


    }



}; ?>

<div class="container mx-auto p-4 ">
    <x-card class="max-w-screen-lg mx-auto mt-4">
        <form class="max-w-xl" wire:submit="changeAvatar" x-data="{ photoPreview: null }">
            <x-card-title >Avatar</x-card-title>
            <x-card-subtitle>Change avatar visible on topbar.</x-card-subtitle>
            <img alt="avatar" class="w-64 aspect-square rounded-full bg-gray-300 mt-8 cursor-pointer
                hover:opacity-75 transition duration-150 ease-in-out
            "
                 @click="$refs.avatarInput.click()"
                 :src="photoPreview || '{{\App\Utils\AvatarUtils::getAvatarPath(auth()->user())}}'"
            />
            <input
                type="file"
                x-ref="avatarInput"
                @change="photoPreview = URL.createObjectURL($refs.avatarInput.files[0])"
                class="hidden"
                wire:model="avatar">
            <div class="flex flex-row gap-4">
                <x-button class="mt-6" wire:click="changeAvatar">
                    Save
                </x-button>
                <x-button color="red" class="mt-6" wire:click="confirmAvatarReset">
                    Reset avatar to Gravatar
                </x-button>
            </div>
            @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </form>
    </x-card>
    <x-card class="max-w-screen-lg mx-auto mt-4">
        <form class="max-w-xl">
            <x-card-title>Profile Information</x-card-title>
            <x-card-subtitle>Update your account's profile information and email address.</x-card-subtitle>
            <div class="mt-4">
                <x-input name="name" label="Name" wire:model="name" type="text"/>
            </div>
            <div class="mt-4">
                <x-input name="email" label="Email" wire:model="email" type="email"/>
            </div>
            <x-button class="mt-6" wire:click="updateInformations">
                Save
            </x-button>
        </form>
    </x-card>
    <x-card class="max-w-screen-lg mx-auto mt-4">
        <div class="max-w-xl">
            <x-card-title>Update pasword</x-card-title>
            <x-card-subtitle>Ensure your account is using a long, random password to stay secure.</x-card-subtitle>
            <div class="mt-4">
                <x-input name="current_password" model="current_password" label="Current password" type="password"/>
            </div>
            <div class="mt-4">
                <x-input name="new_password" model="new_password" label="New password" type="password"/>
            </div>
            <div class="mt-4">
                <x-input name="new_password_confirmation" model="new_password_confirmation" label="Confirm password"
                            type="password"/>
            </div>
            <x-button class="mt-6">
                Save
            </x-button>
        </div>
    </x-card>
</div>
