<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::create([
            'nombre' => 'provider 1',
            'direccion' => 'direccion 1',
            'contacto' => '8713574089'
        ]);

        Provider::create([
            'nombre' => 'provider 2',
            'direccion' => 'direccion 2',
            'contacto' => '8713574090'
        ]);
    }
}
