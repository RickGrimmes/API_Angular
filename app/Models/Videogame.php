<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videogame extends Model
{ 
    use HasFactory;

    protected $hidden = [
        'nombre',
        'unitPrice',
        'description',
        'inStock',
        'discount'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function valorations()
    {
        return $this->hasMany(Valoration::class);
    }

    public function videogameplatforms()
    {
        return $this->hasMany(videogamePlatform::class);
    }

    public function videogameproviders()
    {
        return $this->hasMany(videogameProvider::class);
    }
}
