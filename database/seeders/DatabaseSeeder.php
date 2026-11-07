<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creates the churches (ibeju-lekki-mega, alasia) and one admin per church.
        // Each admin's email and a random password are printed in the terminal.
        $this->call(ChurchSeeder::class);
    }
}
