<?php

namespace Database\Seeders;

use App\Models\Videogame;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideogameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Videogame::create([
            'nombre' => 'GTA 5',
            'genre_id' => 1,
            'unitPrice' => 500,
            'description' => 'tirar balazos bien locotes',
            'inStock' => 29,
            'discount' => 0
        ]);

        Videogame::create([
            'nombre' => 'Red Dead Redemption 2',
            'genre_id' => 1,
            'unitPrice' => 1200,
            'description' => 'te amo arthur morgan',
            'inStock' => 25,
            'discount' => 10
        ]);

        Videogame::create([
            'nombre' => 'Minecraft',
            'genre_id' => 3,
            'unitPrice' => 600,
            'description' => 'stop mob vote, solo separa a la comunidad',
            'inStock' => 50,
            'discount' => 5
        ]);

        Videogame::create([
            'nombre' => 'Cyberpunk 2077',
            'genre_id' => 1,
            'unitPrice' => 900,
            'description' => 'todavia duele haber terminado el juego :c',
            'inStock' => 22,
            'discount' => 15
        ]);
    }
}
