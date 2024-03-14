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

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }
    
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

