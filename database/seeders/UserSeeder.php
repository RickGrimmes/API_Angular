<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Victor Monarrez',
            'email' => 'victor@gmail.com',
            'password' => 'victor',
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Jonathan Vela',
            'email' => 'jona@gmail.com',
            'password' => 'jonathan',
            'role_id' => 1
        ]);

        User::create([
            'name' => 'Ricardo Cabello',
            'email' => 'ricardo@gmail.com',
            'password' => 'ricardo',
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Robloxian Guy',
            'email' => 'robloxian@gmail.com',
            'password' => 'robloxian',
         //   'role_id' => 2
        ]);
    }
}
