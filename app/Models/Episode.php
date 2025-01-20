<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    public $timestamps = false;
//    protected $with = ['urls'];
    protected $fillable = [
        'name',
        'season_id',
        'show_id',
        'tvdb_id',
        'downloaded',
        'episode_order_number',
        'release_date', 'quality', 'needs_update', 'audio_languages', 'subtitle_languages'
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'needs_update' => 'boolean',
    ];

    public function getSerieSO(){
        $season = $this->season;
        return "S".str_pad($season->season_order_number, 2, '0', STR_PAD_LEFT)."E".str_pad($this->episode_order_number, 2, '0', STR_PAD_LEFT);
    }

    public function urls(){
        return $this->hasMany(Url::class);
    }

    public function show() {
        return $this->belongsTo(Show::class);
    }

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($episode) {
            foreach ($episode->urls() as $url) {
                $url->delete();
             }

        });
    }
}
