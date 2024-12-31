<?php

namespace Database\Seeders;

use App\Models\LedgerEntryCategory;
use Illuminate\Database\Seeder;

class LedgerEntryCategorySeeder extends Seeder {

    private $defaults = [
        ['name' => 'Electricity', 'emoji' => '⚡'],
        ['name' => 'Water', 'emoji' => '💧'],
        ['name' => 'Gas', 'emoji' => '🔥'],
        ['name' => 'Internet', 'emoji' => '🌐'],
        ['name' => 'Phone', 'emoji' => '📱'],
        ['name' => 'Rent', 'emoji' => '🏠'],
        ['name' => 'Subscription', 'emoji' => '📅'],
        ['name' => 'Groceries', 'emoji' => '🛒'],
        ['name' => 'Transport', 'emoji' => '🚗'],
        ['name' => 'Health', 'emoji' => '💊'],
        ['name' => 'Insurance', 'emoji' => '🛡️'],
        ['name' => 'Education', 'emoji' => '🎓'],
        ['name' => 'Entertainment', 'emoji' => '🎉'],
        ['name' => 'Clothing', 'emoji' => '👕'],
        ['name' => 'Gifts', 'emoji' => '🎁'],
        ['name' => 'Other', 'emoji' => '🔍'],
    ];



    public function run(): void {
        foreach ($this->defaults as $default) {
            LedgerEntryCategory::create($default);
        }
    }
}
