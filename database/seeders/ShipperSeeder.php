<?php

namespace Database\Seeders;

use App\Models\Shipper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipper::create([
            'name' => 'Shipper 1',
            'direccion' => 'direccion 1',
            'email_contacto' => 'emailshipper1@gmail.com'
        ]);

        Shipper::create([
            'name' => 'Shipper 2',
            'direccion' => 'direccion 2',
            'email_contacto' => 'emailshipper2@gmail.com'
        ]);

        Shipper::create([
            'name' => 'Shipper 3',
            'direccion' => 'direccion 3',
            'email_contacto' => 'emailshipper3@gmail.com'
        ]);
    }
}
