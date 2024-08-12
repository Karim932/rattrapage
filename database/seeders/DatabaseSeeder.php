<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // User::factory()->count(30)->create(); // créer 30 users
        AdhesionBenevole::factory()->count(25)->create();
        AdhesionCommercant::factory()->count(25)->create();

    }
}
