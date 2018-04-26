<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'dob',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function usermovies()
    {
        return $this->hasMany('App\UserMovie');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Recommendation');
    }
}