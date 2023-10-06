<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'url',
        'movie_id',
        'episode_id',
        'downloaded',
        'invalid',
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'invalid' => 'boolean',
    ];
}
