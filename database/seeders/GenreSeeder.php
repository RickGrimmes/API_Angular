<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::create([
            'name' => 'Accion',
            'description' => 'description 1'
        ]);

        Genre::create([
            'name' => 'Suspenso',
            'description' => 'description 2'
        ]);

        Genre::create([
            'name' => 'Terror',
            'description' => 'description 3'
        ]);
    }
}
