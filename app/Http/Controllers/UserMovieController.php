<?php

namespace App\Http\Controllers;

use Auth;
use App\Movie;
use App\UserMovie;
use Illuminate\Http\Request;

class UserMovieController extends Controller
{
    // authorisation
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get and return the movies rated by the current user
    // #return \Illuminate\Http\Response
    public function getUserMovies()
    {
        $all_rated_by_user = UserMovie::where(['user_id' => Auth::user()->id])->get();
        return response()->json([
            'all_rated_by_user'    => $all_rated_by_user,
        ], 200);
    }
    // add new movie with rating to DB
    public function store(Request $request)
    {
        // create new record in movie table
        $movie = new Movie();
        $movie->tmdb_id = $request->a;
        $movie->save();

        // create new record in user_movies table
        $userMovie = new UserMovie();
        $userMovie->user_id = Auth::user()->id;
        $userMovie->movie_id = $request->a;
        $userMovie->ratio = $request->b;
        $userMovie->save();     
    }

    // DOESN'T WORK
    // update existing movie record in DB
    public function update(Request $request)
    {
        $userMovie = new UserMovie();

        // UserMovie::where(['user_id' => Auth::user()->id,
        //                   'movie_id' => $request->a])->get();

        // get the movie from junction table to update it
        $userMovie = DB::table('user_movies')->where([
            ['user_id', '=', 'Auth::user()->id'],
            ['movie_id', '=', '$request->a'],
        ])->get();

        $userMovie->ratio = $request->b;
        $userMovie->save();
    }
}
