<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = [
        'user_id',
        'tmdb_id',
        'avg_ratio',
    ];

    public function user()
    {
        // 'user_id' is used us foreign key by default
        return $this->belongsTo('App\User');
    }
}
