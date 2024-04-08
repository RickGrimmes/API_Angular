<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'contacto',
        'estado'
    ];

    public function provider()
    {
        return $this->hasMany(Order::class);
    }
}
