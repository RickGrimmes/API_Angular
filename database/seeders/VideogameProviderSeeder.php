<?php

namespace Database\Seeders;

use App\Models\videogameProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideogameProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        videogameProvider::create([
            'videogame_id' => '2',
            'provider_id' => '1'
        ]);

        videogameProvider::create([
            'videogame_id' => '1',
            'provider_id' => '2'
        ]);

        videogameProvider::create([
            'videogame_id' => '4',
            'provider_id' => '2'
        ]);

        videogameProvider::create([
            'videogame_id' => '3',
            'provider_id' => '2'
        ]);
    }
}
