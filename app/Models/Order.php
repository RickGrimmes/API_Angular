<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipper_id',
        'state_id'
    ];

    public function order()
    {
        return $this->hasMany(orderDetail::class);
    }

    public function orderBTM()
    {
        return $this->belongsToMany(Shipper::class);
    }
    
    public function orderBTM1()
    {
        return $this->belongsToMany(State::class);
    }
}
