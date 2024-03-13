<?php

namespace Database\Seeders;

use App\Models\videogamePlatform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideogamePlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        videogamePlatform::create([
            'platform_id' => '2',
            'videogame_id' => '1'
        ]);

        videogamePlatform::create([
            'platform_id' => '2',
            'videogame_id' => '2'
        ]);

        videogamePlatform::create([
            'platform_id' => '2',
            'videogame_id' => '3'
        ]);

        videogamePlatform::create([
            'platform_id' => '2',
            'videogame_id' => '4'
        ]);

        videogamePlatform::create([
            'platform_id' => '4',
            'videogame_id' => '3'
        ]);

        videogamePlatform::create([
            'platform_id' => '4',
            'videogame_id' => '4'
        ]);

        videogamePlatform::create([
            'platform_id' => '1',
            'videogame_id' => '1'
        ]);

        videogamePlatform::create([
            'platform_id' => '5',
            'videogame_id' => '3'
        ]);
    }
}
