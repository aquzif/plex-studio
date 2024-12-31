<?php

namespace App\Livewire;

use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Component;

class ToasterComponent extends Component {

    public $toasts = [

    ];


    public function render()
    {
        if(session()->has('flashed-toast')){
            $toast = session('flashed-toast');

            if($toast['type'] === 'success')
                $this->dispatch('toast-success',[
                    'message' => $toast['message'],
                    'title' => $toast['title']
                ]);
            else if($toast['type'] === 'warning')
                $this->dispatch('toast-warning',[
                    'message' => $toast['message'],
                    'title' => $toast['title']
                ]);
            else if($toast['type'] === 'error')
                $this->dispatch('toast-error',[
                    'message' => $toast['message'],
                    'title' => $toast['title']
                ]);
            session()->forget('flashed-toast');
        }

        return view('livewire.components.toaster-component');
    }

    #[On('toaster-send-warning')]
    public function sendWarningToast($id,$title,$message = ''){
        $this->flushOldToasts();
        $this->toasts[] = [
            'id' => $id,
            'type' => 'warning',
            'show' => true,
            'title' => $title,
            'message' => $message
        ];
    }

    #[On('toaster-send-success')]
    public function sendSuccessToast($id,$title,$message = ''){
        $this->flushOldToasts();
        $this->toasts[] = [
            'id' => $id,
            'type' => 'success',
            'show' => true,
            'title' => $title,
            'message' => $message
        ];
    }

    #[On('toaster-send-error')]
    public function sendErrorToast($id,$title,$message = ''){
        $this->flushOldToasts();
        $this->toasts[] = [
            'id' => $id,
            'type' => 'error',
            'show' => true,
            'title' => $title,
            'message' => $message
        ];
    }

    private function flushOldToasts(){
        $this->toasts = array_filter($this->toasts,function($toast){
            return $toast['show'];
        });

        if(count($this->toasts) >= 3){
            array_shift($this->toasts);
        }
    }

    #[On('toaster-hide-toast')]
    public function hideToast($id){
        $this->toasts = array_map(function($toast) use ($id){
            if($toast['id'] == $id){
                $toast['show'] = false;
            }
            return $toast;
        },$this->toasts);
    }
}
