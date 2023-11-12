<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Show extends Model
{
    public $timestamps = false;
    protected $with = ['seasons','urls'];

    protected $fillable = [
        'name',
        'type',
        'tvdb_id',
        'thumb_path',
        'downloaded',
        'favourite',
        'seasons_type',
        'quality',
        'needs_update',
        'audio_languages',
        'subtitle_languages',
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'favourite' => 'boolean',
        'needs_update' => 'boolean',
    ];

    public function seasons(){
        return $this->hasMany(Season::class)->orderBy('season_order_number');
    }

    public function urls(){
        return $this->hasMany(Url::class,'movie_id','id');
    }


    public static function boot() {
        parent::boot();

        static::deleting(function($show) {
            Storage::delete($show->thumb_path);
            foreach ($show->seasons()->get() as $item) {
                $item->delete();
            }

        });
    }

}
