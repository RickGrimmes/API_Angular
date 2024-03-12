<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valoration extends Model
{
    use HasFactory;

    protected $hidden = [
        'user_id',
        'videogame_id',
        'estrellas'
    ];

    public function valorationBTU() 
    {
        return $this->belongsTo(User::class);
    }

    public function valorationBTV() 
    {
        return $this->belongsTo(Videogame::class);
    }
}
