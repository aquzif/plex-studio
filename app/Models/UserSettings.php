<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $fillable = [
        'sort_by',
        'sort_type',
        'show_only_incomplete',
        'show_only_favourites',
        'hide_downloaded_episodes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'show_only_incomplete' => 'boolean',
            'show_only_favourites' => 'boolean',
        ];
    }
}
