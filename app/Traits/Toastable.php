<?php

namespace App\Traits;

trait Toastable {

    public function sendSuccessToast($title, $message = '',$flash = false){

        if($flash){
            session()->put('flashed-toast',[
                'type' => 'success',
                'message' => $message,
                'title' => $title
            ]);
            return null;
        }
        return $this->dispatch('toast-success',[
            'message' => $message,
            'title' => $title
        ]);
    }

    public function sendWarningToast($title, $message = ''){
        return $this->dispatch('toast-warning',[
            'message' => $message,
            'title' => $title
        ]);
    }

    public function sendErrorToast($title, $message = ''){
        return $this->dispatch('toast-error',[
            'message' => $message,
            'title' => $title
        ]);
    }



}
