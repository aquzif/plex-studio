<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(){
        return $this->role === 'admin';
    }

    public function getSettings() {
        return UserSettings::where('user_id',$this->id)->firstOrCreate([],[
            'user_id' => $this->id,
            'sort_by' => 'name',
            'sort_type' => 'asc',
            'hide_downloaded_episodes' => false,
            'show_only_favourites' => false,
            'show_only_incomplete' => true,
        ]);
    }

}
