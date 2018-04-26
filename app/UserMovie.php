<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMovie extends Model
{

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'tmdb_id',
        'ratio',
        'hidden',
        'watchlater', 
    ];

    public function user()
    {
        // 'user_id' is used us foreign key by default
        return $this->belongsTo('App\User');
    }
}
