<?php

use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {


    //check if user has permission to view this page, if not redirect to last page and show toast
    public function booted()
    {
        if (!auth()->user()->isAdmin()) {
            $this->toast()
                ->error('Insufficient permissions','You do not have permission to view this page')
                ->flash()
                ->send();
            return $this->redirect(route('dashboard'));
        }
    }

}; ?>

<div>
</div>
