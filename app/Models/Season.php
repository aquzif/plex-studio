<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Season extends Model
{
    public $timestamps = false;
//    protected $with = ['episodes'];

    protected $fillable = [
        'show_id',
        'tvdb_id',
        'name',
        'season_order_number',
        'thumb_path',
        'quality', 'needs_update', 'audio_languages', 'subtitle_languages','notes'
    ];

    protected $casts = [
        'needs_update' => 'boolean',
    ];

    public function episodes(){
        return $this->hasMany(Episode::class)->orderBy('episode_order_number');
    }

    public function howManyEpisodes() {
        return $this->episodes()->count();
    }

    public function howManyReleasedEpisodes() {
        return $this->episodes()->where('release_date', '<=', now())->count();
    }

    public function howManyDownloadedEpisodes() {
        return $this->episodes()->where('downloaded', true)->count();
    }

    public function howManyDownloadedReleasedEpisodes() {
        return $this->episodes()->where('downloaded', true)->where('release_date', '<=', now())->count();
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
