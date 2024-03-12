<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videogameProvider extends Model
{
    use HasFactory;

    protected $hidden = [
        'videogame_id',
        'provider_id'
    ];

    public function videogameProviderBTV()
    {
        return $this->belongsTo(Videogame::class);
    }

    public function videogameProviderBTP()
    {
        return $this->belongsTo(Provider::class);
    }
}
