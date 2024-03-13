<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'rol' => 'Admin'
        ]);

        Role::create([
            'rol' => 'Cliente'
        ]);

        Role::create([
            'rol' => 'Visitante'
        ]);
    }
}
