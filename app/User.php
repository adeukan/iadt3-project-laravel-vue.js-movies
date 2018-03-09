<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * #var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dob',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * #var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function movies()
    {
        // user_movies  - joining table
        // user_id      - foreign key to 'users' table
        // movie_id     - foreign key to 'movies' table
        return $this->belongsToMany('App\Movie', 'user_movies', 'user_id', 'movie_id');
    }

}