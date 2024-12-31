<?php

namespace App\Traits;

trait Modalable {

    public function showModal($name)
    {
        $this->dispatch('openModal', $name);
    }

    public function closeModal($name)
    {
        $this->dispatch('closeModal', $name);
    }

}
