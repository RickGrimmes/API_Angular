<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Platform::create([
            'plataforma' => 'PS4'
        ]);

        Platform::create([
            'plataforma' => 'PS5'
        ]);

        Platform::create([
            'plataforma' => 'Xbox One'
        ]);

        Platform::create([
            'plataforma' => 'Xbox Series'
        ]);

        Platform::create([
            'plataforma' => 'Nintendo Switch'
        ]);
    }
}
