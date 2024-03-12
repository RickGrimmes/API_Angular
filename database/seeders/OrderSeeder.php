<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'user_id' => 2,
            'shipper_id' => 1,
            'state_id' => 2
        ]);

        Order::create([
            'user_id' => 3,
            'shipper_id' => 3,
            'state_id' => 3
        ]);

        Order::create([
            'user_id' => 4,
            'shipper_id' => 1,
            'state_id' => 1
        ]);
    }
}
