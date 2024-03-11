<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $hidden = [
        'estado'
    ];

    public function state()
    {
        return $this->hasMany(Order::class);
    }
}
