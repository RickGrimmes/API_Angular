<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videogameProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'videogame_id',
        'provider_id'
    ];

    public function videogame()
    {
        return $this->belongsTo(Videogame::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
