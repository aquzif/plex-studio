<?php

namespace App\Traits;

trait Loadable
{
    public function showLoader()
    {
        $this->dispatch('show-loader');
    }

    public function hideLoader()
    {
        $this->dispatch('hide-loader');
    }
}
