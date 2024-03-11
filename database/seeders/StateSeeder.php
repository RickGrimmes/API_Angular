<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::create([
            'estado' => 'En proceso'
        ]);

        State::create([
            'estado' => 'En envío'
        ]);

        State::create([
            'estado' => 'Entregado'
        ]);

        State::create([
            'estado' => 'Cancelado'
        ]);
    }
}
