<?php

namespace Database\Seeders;

use App\Models\orderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        orderDetail::create([
            'order_id' => 3,
            'videogame_id' => 1,
            'quantity' => 1,
            'totalPrice' => 500
        ]);
    }
}
