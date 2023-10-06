<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Season extends Model
{
    public $timestamps = false;
    protected $with = ['episodes'];

    protected $fillable = [
        'show_id',
        'tvdb_id',
        'name',
        'season_order_number',
        'thumb_path',
    ];

    public function episodes(){
        return $this->hasMany(Episode::class)->orderBy('episode_order_number');

    }

    //delete all episodes when season is deleted, and unlink all files
    public static function boot() {
        parent::boot();

        static::deleting(function($season) {
            Storage::delete($season->thumb_path);
            foreach ($season->episodes() as $episode) {
                $episode->delete();
            }


        });
    }
}
