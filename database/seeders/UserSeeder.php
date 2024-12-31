<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Mariusz Sternak',
            'email' => 'aquzif@gmail.com',
            'password' => bcrypt('asdzxcasdzxc123'),
        ]);
    }
}
