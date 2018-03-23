<?php

namespace App\Http\Controllers;

use Auth;
use App\Movie;
use App\UserMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class UserMovieController extends Controller
{
    // authorisation
    public function __construct()
    {
        $this->middleware('auth');
    }

    // check the DB for existing recommendations
    public function checkRecommendations() {
        
        // SQL - get (and count) previously saved recommendations for "me" 
        $sql =  'SELECT movie_id, avg_ratio, COUNT(*) as qty
                FROM recommendations
                WHERE user_id = ' . Auth::user()->id ;

        $response = DB::select($sql);

        // return previously saved recommendations if they exist
        if($response[0]->qty > 0) {
            return response()->json([
                'recommended' => $response,
            ], 200);
        }
        // get number of movies rated by "me"
        else {
            $sql =  'SELECT COUNT(*) as qty
                    FROM user_movies
                    WHERE user_id = ' . Auth::user()->id;

            $response = DB::select($sql);

            // return falure response with the number of movies rated by "me"
            return response()->json([
                'recommended' => 'nothing',
                'rated'       => $response[0]->qty,
            ], 200);
        }
    }

    // getting the recommendations
    public function getRecommendations() {

        // SQL - get users with similar set of movies
        $sql = 'SELECT t2.user_id, COUNT(t2.movie_id) AS same_movies_num
             FROM user_movies t1 INNER JOIN user_movies t2
                ON t1.user_id = ' . Auth::user()->id . 
                ' AND t2.user_id != ' . Auth::user()->id .
                ' AND t1.movie_id = t2.movie_id
             GROUP BY t2.user_id
             HAVING same_movies_num >= 30
             ORDER BY COUNT(t2.movie_id) DESC
             LIMIT 100';  
        
        // get the users with the set of movies most similar to mine (sorted by the number of same movies)
        // each object includes the user_id and the number of same movies
        $same_movie_users = DB::select($sql);

        // handle possible absence of users with similar set of movies (will do it later)

        // array of friends
        $friends_array = [];

        // check each user and possibly add to friends
        foreach ($same_movie_users as $user) {
            
            // SQL - for each user, count the number of similar ratings (for our common movies)
            $sql = 'SELECT COUNT(t1.movie_id) AS equal_ratios_num
                    FROM user_movies t1 INNER JOIN user_movies t2
                        ON t1.user_id = ' . Auth::user()->id .  
                        ' AND t2.user_id = ' . $user->user_id .
                        ' AND t1.movie_id = t2.movie_id
                        AND (t1.ratio - t2.ratio) BETWEEN -1 AND 1';

            $response = DB::select($sql);

            // similarity factor = the number of movies with similar rating / total number of common movies
            $factor = ($response[0]->equal_ratios_num) / $user->same_movies_num;

            // add a user to "friend list" if the similarity factor is more than 0.7
            if($factor >= 0.7) {

                // create a friend object
                $friend = new stdClass();
                // add the ID and Factor as fields of the object
                $friend->user_id = $user->user_id;
                $friend->factor = $factor;
                // add the object to "friend list"
                array_push($friends_array, $friend);
            }
        }

        // interrupt the operation if the "friend list" is empty
        if($friends_array == null){
            // return failure response
            return response()->json([
                'recommended' => 'nothing',
            ], 200);
        }

        // sort the "friends" (objects) in the array by the Factor field (the similarity of taste)
        usort($friends_array, function($f1, $f2)
                                {
                                    if ($f1->factor == $f2->factor)
                                        return 0;
                                    
                                    return ($f1->factor > $f2->factor) ? -1 : 1;
                                }
        );

        // take the first 10 (or 100 ?) members of the $friends_array (not ready yet)

        // put all friends IDs in one line separated by commas (argument for SQL)
        $arguments = "";
        foreach ($friends_array as $friend) {
            $arguments = $arguments . $friend->user_id . ',';
        }
        // remove the comma after the last friend ID
        $arguments = mb_substr($arguments, 0, -1);

        // SQL - get the movies (which I have not seen before) with the highest ratings among my friends
        // the movies should have a rating from 3 to 5 (from 4 to 5 is better), and at least 3 "friends" should have it in their list
        $sql =  'SELECT t2.movie_id, AVG(t2.ratio) AS avg_ratio
                FROM user_movies t1 INNER JOIN user_movies t2
                    ON t1.user_id = ' . Auth::user()->id . 
                    ' AND t2.user_id IN(' . $arguments . ')
                    AND t2.movie_id NOT IN (SELECT movie_id
                                            FROM user_movies
                                            WHERE user_id = '. Auth::user()->id .')
                GROUP BY(t2.movie_id)
                HAVING AVG(t2.ratio) >= 3 AND COUNT(DISTINCT t2.user_id) > 3
                ORDER BY AVG(t2.ratio) DESC';

        $response = DB::select($sql);

        // if there are no recommendations for "me"
        if(count($response) == 0) {
            return response()->json([
                'recommended' => 'nothing',
            ], 200);
        }
        // if "I" have recommendations
        else {
            // return a sorted array of movies (recommendations)
            return response()->json([
                'recommended' => $response,
            ], 200);
        }
    } // getRecommendations()


    // save recommended movies in DB --------------------------------------------------------
    public function saveRecommendations(Request $request) {

        // remove my all previous recommendations from DB to avoid errors when overwriting
        DB::table('recommendations')->where('user_id', '=',  Auth::user()->id)->delete();

        // the array with the recommendations
        $recommended_array = $request->input();

        // save each recommended movie in DB
        for ($i = 0; $i < count($recommended_array); $i++) {
            // extract every next movie from the array
            $recommended_movie = $recommended_array[$i];
            // and record to DB
            DB::table('recommendations')->insert([
                                                'user_id' => Auth::user()->id,
                                                'movie_id' => $recommended_movie['movie_id'],
                                                'avg_ratio' => $recommended_movie['avg_ratio']
                                                ]);
        }                                     
    } // saveRecommendations()


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
        // check if this movie is in the "movies" table
        $movie = Movie::where(["tmdb_id" => $request->input("tmdb_id")])->first();

        if($movie == null) {
            // create new record in movie table
            $movie = new Movie();
            $movie->tmdb_id = $request->input("tmdb_id");
            $movie->save();
        }

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
