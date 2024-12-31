<?php

namespace App\Livewire;

use App\Models\Ledger;
use Livewire\Component;

class NewLedgerModal extends ModalComponent {
    public $name = 'new-ledger-modal';
    #[\Livewire\Attributes\Validate(['required', 'string', 'min:6', 'max:255'])]
    public $ledgerName;
    #[\Livewire\Attributes\Validate(['required', 'exists:currencies,currency_code'])]
    public $currency;

    public function mount()
    {

    }

    public function createLedger()
    {


    }

    public function render()
    {
        return view('livewire.modals.new-ledger-modal');
    }
}
