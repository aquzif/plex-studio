<?php

use App\Models\Settings;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use \App\Traits\Toastable;

    public string $tvdbApiKey;
    public string $tvdbApiPin;
    public string $jdownloaderEmail;
    public string $jdownloaderPassword;
    public string $jdownloaderDevice;
    public $jDownloaderDevices = [];
    public bool $jdownloaderConnected = false;


    public function mount()
    {
        $settings = Settings::getSettings();

        $this->tvdbApiKey = $settings->tvdb_api_key;
        $this->tvdbApiPin = $settings->tvdb_api_pin;
        $this->jdownloaderEmail = $settings->jdownloader_email;
        $this->jdownloaderPassword = $settings->jdownloader_password;
        $this->jdownloaderDevice = $settings->jdownloader_device;

        if($this->jdownloaderDevice){
            $this->jDownloaderDevices = [
                [
                    'label' => $this->jdownloaderDevice,
                    'value' => $this->jdownloaderDevice
                ]
            ];
        }

    }

    public function updateTVDB() {

        $this->validate([
            'tvdbApiKey' => 'required|string|max:50',
            'tvdbApiPin' => 'required|string|max:50',
        ]);

        try{
            $settings = Settings::getSettings();
            $settings->tvdb_api_key = $this->tvdbApiKey;
            $settings->tvdb_api_pin = $this->tvdbApiPin;
            $settings->save();
            $this->sendSuccessToast('TVDB settings updated');
        }catch (\Exception $e){
            $this->sendErrorToast('Error updating TVDB settings');
        }
    }

    public function validatejDownloaderConfiguration($quiet = false) {
        $this->validate([
            'jdownloaderEmail' => 'required|email|string|max:50',
            'jdownloaderPassword' => 'required|string|max:50',
        ]);

        $jsUtils = new \App\Utils\JDownloaderUtils();

        try{
            $settings = Settings::getSettings();
            $settings->jdownloader_email = $this->jdownloaderEmail;
            $settings->jdownloader_password = $this->jdownloaderPassword;


            if(!$jsUtils->connect($this->jdownloaderEmail, $this->jdownloaderPassword)){
                $this->sendErrorToast('Error connecting to JDownloader');
                return;
            }
            $jsUtils->enumerateDevices();
            $newDevices = [];
            foreach ($jsUtils->getDevices() as $item) {

                $newDevices[] = [
                    'label' => $item['name'],
                    'value' => $item['name']
                ];
            }

            $this->jDownloaderDevices = $newDevices;

            $settings->save();
            $this->jdownloaderConnected = true;
            $this->sendSuccessToast('JDownloader connected');
        }catch (\Exception $e){
            $this->sendErrorToast('Error updating JDownloader settings');
        }
    }

    public function updateJDownloader() {
        $this->validate([
            'jdownloaderDevice' => 'required|string|max:50',
        ]);

        $this->validatejDownloaderConfiguration();
        $jdUtils = new \App\Utils\JDownloaderUtils();
        $jdUtils->connect($this->jdownloaderEmail, $this->jdownloaderPassword);

        if(!$jdUtils->setDeviceName($this->jdownloaderDevice)){
            $this->sendErrorToast('Error setting JDownloader device');
            return;
        }

        try{
            $settings = Settings::getSettings();
            $settings->jdownloader_device = $this->jdownloaderDevice;
            $settings->save();
            $this->sendSuccessToast('JDownloader settings updated');
        }catch (\Exception $e){
            $this->sendErrorToast('Error updating JDownloader device');
        }
    }



}; ?>

<div class="container mx-auto p-4 ">
    <x-card class="max-w-screen-lg mx-auto mt-4">
        <form class="max-w-xl"  wire:submit="updateTVDB" >
            <x-card-title>TVDB Integration</x-card-title>
            <x-card-subtitle>Configure TVDB API credentials from
                <a target="_blank" class="text-blue-500" href="https://www.thetvdb.com/dashboard/account/subscription" >here</a>
            </x-card-subtitle>
            <div class="mt-4">
                <x-input name="tvdbApiKey" label="TVDB API key" wire:model="tvdbApiKey" type="text"/>
            </div>
            <div class="mt-4">
                <x-input name="tvdbApiPin" label="TVDB API pin" wire:model="tvdbApiPin" type="text"/>
            </div>
            <x-button class="mt-6">
                Save
            </x-button>
        </form>
    </x-card>
    <x-card class="max-w-screen-lg mx-auto mt-4">
        <div class="max-w-xl">
            <form class="max-w-xl"  >
                <x-card-title>My.jDownloader Integration</x-card-title>
                <x-card-subtitle>Enter email and password used on
                    <a target="_blank" class="text-blue-500" href="https://my.jdownloader.org/login.html" >my.jDownloader</a>
                </x-card-subtitle>

                <div class="mt-4">
                    <x-input name="jdownloaderEmail" label="jDownloader email" wire:model="jdownloaderEmail" type="email"/>
                </div>
                <div class="mt-4">
                    <x-input name="jdownloaderPassword" label="jDownloader password" wire:model="jdownloaderPassword" type="password"/>
                </div>
                <x-button class="mt-6" wire:click.prevent="validatejDownloaderConfiguration()" >
                    Check settings / fetch devices
                </x-button>
                <div class="mt-4" >
                    <x-select name="jdownloaderDevice" label="Device" model="jdownloaderDevice" :options="$jDownloaderDevices"  />
                </div>
                <x-button class="mt-6"
                          :disabled="!$jdownloaderConnected"
                          wire:click.prevent="updateJDownloader()()"
                >
                    Save
                </x-button>
            </form>
        </div>
    </x-card>
</div>
