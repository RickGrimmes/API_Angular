<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;

    protected $hidden = [
        'name',
        'direccion',
        'email_contacto'
    ];

    public function shipper()
    {
        return $this->hasMany(Order::class);
    }
}
