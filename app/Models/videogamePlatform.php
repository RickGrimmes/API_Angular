<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videogamePlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id',
        'videogame_id'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function videogame()
    {
        return $this->belongsTo(Videogame::class);
    }
}
