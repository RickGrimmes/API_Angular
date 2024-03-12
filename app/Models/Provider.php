<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'direccion',
        'contacto'
    ];

    public function provider()
    {
        return $this->hasMany(Order::class);
    }
}
