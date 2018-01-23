<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
	// protected $fillable = [
 	//        'user_id'
 	//    ];

	public function users() 
    {
    	return $this->belongsToMany('App\User');
    }
}
