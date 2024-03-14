<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'videogame_id',
        'quantity',
        'totalPrice'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function videogame()
    {
        return $this->belongsTo(Videogame::class);
    }
}
