<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class RequestLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'logs';
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'http_verb',
        'route',
        'query',
        'data',
        'request_time',
    ];

}
