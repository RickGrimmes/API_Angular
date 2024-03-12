<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDetail extends Model
{
    use HasFactory;

    protected $hidden = [
        'order_id',
        'videogame_id',
        'quantity',
        'totalPrice'
    ];

    public function orderDetail()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetailHMV()
    {
        return $this->belongsTo(Videogame::class);
    }
}
