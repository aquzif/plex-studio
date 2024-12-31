<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    protected $signature = 'setup';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->info('Reseting migrations');
        $this->call('migrate:reset');
        $this->info('Migrating');
        $this->call('migrate');
        $this->info('Inserting currencies');
        $this->call('db:seed', ['--class' => 'CurrencySeeder']);
        $this->info('Inserting user');
        $this->call('db:seed', ['--class' => 'UserSeeder']);
    }
}
