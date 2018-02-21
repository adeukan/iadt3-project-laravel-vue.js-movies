<?php

namespace App\Http\Controllers;

use Auth;
use App\Movie;
use App\UserMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserMovieController extends Controller
{
    // authorisation
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get top 50 IDs of users with similar movies
    public function getSameMovieUsers() {

        $sql = 'SELECT t1.user_id
             FROM user_movies t1 INNER JOIN user_movies t2
                ON t1.user_id != ' . Auth::user()->id . 
                ' AND t2.user_id = ' . Auth::user()->id .
                ' AND t1.movie_id = t2.movie_id 
             GROUP BY t1.user_id 
             ORDER BY COUNT(t2.movie_id) DESC
             LIMIT 10';

        // response is an array of Objects, but we need an array of IDs
        $response = DB::select($sql);

        // new array to store IDs
        $same_movie_users = array();

        // get user IDs from $response and put them into new array
        foreach ($response as $user) {
          array_push($same_movie_users, $user->user_id);
        }

        // return the array with IDs
        return response()->json([
            'same_movie_users'  => $same_movie_users,
        ], 200);
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

    public function getHighRatedMovies() {
        
    }

    public function getUserMovieRatio(Request $request)
    {
        // $user_movie = UserMovie::where([

        //     ['user_id' => Auth::user()->id],
        //     ['tmdb_id' => $request->input("tmdb_id")]

        // )->get();




        // return response()->json([
        //     'user_movie_ratio'    => $user_movie_ratio,
        // ], 200);



    }

    // add new movie with rating to DB
    public function store(Request $request)
    {
        // create new record in movie table
        $movie = new Movie();
        $movie->tmdb_id = $request->input("tmdb_id");
        $movie->save();

        // create new record in user_movies table
        $userMovie = new UserMovie();
        $userMovie->user_id = Auth::user()->id;
        $userMovie->movie_id = $request->input("tmdb_id");
        $userMovie->ratio = $request->input("user_rating");
        $userMovie->save();     
    }

    // update existing movie record in DB
    public function update(Request $request)
    {
        $userMovie = new UserMovie();

        // get the movie from junction table to update it
        $userMovie = UserMovie::where([
            'user_id' => Auth::user()->id,
            'movie_id' => $request->input("tmdb_id")
        ])->first();

        $userMovie->ratio = $request->input("user_rating");
        $userMovie->save();
    }
}
