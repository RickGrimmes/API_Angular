<?php

namespace Database\Seeders;

use App\Models\Valoration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ValorationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Valoration::create([
            'user_id' => 2,
            'videogame_id' => 2,
            'estrellas' => 5
        ]);

        Valoration::create([
            'user_id' => 2,
            'videogame_id' => 1,
            'estrellas' => 5
        ]);

        Valoration::create([
            'user_id' => 2,
            'videogame_id' => 3,
            'estrellas' => 5
        ]);

        Valoration::create([
            'user_id' => 2,
            'videogame_id' => 4,
            'estrellas' => 5
        ]);

        Valoration::create([
            'user_id' => 4,
            'videogame_id' => 1,
            'estrellas' => 5
        ]);

        Valoration::create([
            'user_id' => 5,
            'videogame_id' => 1,
            'estrellas' => 1
        ]);

        Valoration::create([
            'user_id' => 5,
            'videogame_id' => 3,
            'estrellas' => 1
        ]);

        Valoration::create([
            'user_id' => 4,
            'videogame_id' => 1,
            'estrellas' => 3
        ]);
    }
}
