<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'tvdb_api_key',
        'tvdb_api_pin',
        'jdownloader_email',
        'jdownloader_password',
        'jdownloader_device'
    ];

    //encrypt fields on save, and decrypt on get (use boot)
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->tvdb_api_key = encrypt($model->tvdb_api_key);
            $model->tvdb_api_pin = encrypt($model->tvdb_api_pin);
            $model->jdownloader_email = encrypt($model->jdownloader_email);
            $model->jdownloader_password = encrypt($model->jdownloader_password);
            $model->jdownloader_device = encrypt($model->jdownloader_device);
        });

        static::retrieved(function ($model) {
            $model->tvdb_api_key = decrypt($model->tvdb_api_key);
            $model->tvdb_api_pin = decrypt($model->tvdb_api_pin);
            $model->jdownloader_email = decrypt($model->jdownloader_email);
            $model->jdownloader_password = decrypt($model->jdownloader_password);
            $model->jdownloader_device = decrypt($model->jdownloader_device);
        });
    }


    public static function getSettings(): Settings
    {
        $settings = Settings::first();
        if(!$settings){
            Settings::create([
                'tvdb_api_key' => '',
                'tvdb_api_pin' => '',
                'jdownloader_email' => '',
                'jdownloader_password' => '',
                'jdownloader_device' => ''
            ]);
            $settings = Settings::first();
        }
        return $settings;

    }

}
