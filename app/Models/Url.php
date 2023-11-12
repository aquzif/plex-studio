<?php

namespace App\Models;

use App\Utils\UrlUtils;
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
        'last_validated_date',
        'auto_valid',
        'host',
        'quality'
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'invalid' => 'boolean',
        'auto_valid' => 'boolean',

    ];

    public function episode(){
        return $this->belongsTo(Episode::class);
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::creating(function ($model){
            $model->last_validated_date = '2023-10-10';
            if(strlen($model->url) > 0){
                $model->host = parse_url($model->url)['host'];
                $model->quality = UrlUtils::getQualityFromUrl($model->url);
            }
        });
        static::updating(function ($model){
            if(strlen($model->url) > 0){
                $model->host = parse_url($model->url)['host'];
                $model->quality = UrlUtils::getQualityFromUrl($model->url);
            }
        });
    }


}
