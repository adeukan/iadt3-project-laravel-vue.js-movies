<?php

namespace App\Http\Controllers;

use Auth;
use App\Movie;
use App\UserMovie;
use App\User;
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

    
    public function getMyMovies() {

        $my_movies = UserMovie::where([
                    'user_id' => Auth::user()->id,
            ])->get();

        return response()->json([
                'my_movies' => $my_movies,
            ]);
    }

    public function getMyUser() {

        $my_user = User::where([
                    'id' => Auth::user()->id,
            ])->get();

        return response()->json([
                'my_user' => $my_user,
            ], 200);
    }

    // // get and return the hidden movies
    // // #return \Illuminate\Http\Response
    // public function getHiddenMovies()
    // {
    //     $hidden_movies = UserMovie::where([
    //         'user_id' => Auth::user()->id,
    //         'hidden' => '1',
    //         'watchlater' => '0'
    //     ])->get();

    //     return response()->json([
    //         'hidden_movies'    => $hidden_movies,
    //     ], 200);
    // }


    // get and return the movies rated by the current user
    public function getRatedMovies()
    {
        $rated_movies = UserMovie::where([
            'user_id' => Auth::user()->id,
            'hidden' => '0',
            'watchlater' => '0'
        ])->get();

        return response()->json([
            'rated_movies'    => $rated_movies,
        ], 200);
    }


    // check the DB for existing recommendations
    public function checkRecommendations() {
        
        // SQL - get previously saved recommendations for "me" 
        $sql =  'SELECT tmdb_id, avg_ratio
                FROM recommendations
                WHERE user_id = ' . Auth::user()->id ;

        $response = DB::select($sql);

        // return previously saved recommendations if they exist
        if(count($response) > 0) {
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
        $sql = 'SELECT t2.user_id, COUNT(t2.tmdb_id) AS same_movies_num
             FROM user_movies t1 INNER JOIN user_movies t2
                ON t1.user_id = ' . Auth::user()->id . 
                ' AND t2.user_id != ' . Auth::user()->id .
                ' AND t1.tmdb_id = t2.tmdb_id
             GROUP BY t2.user_id
             HAVING same_movies_num >= 30
             ORDER BY COUNT(t2.tmdb_id) DESC
             LIMIT 20';  
        
        // get the users with the set of movies most similar to mine (sorted by the number of same movies)
        // each object includes the user_id and the number of same movies
        $same_movie_users = DB::select($sql);

        // handle possible absence of users with similar set of movies (will do it later)

        // array of friends
        $friends_array = [];

        // check each user and possibly add to friends
        foreach ($same_movie_users as $user) {
            
            // SQL - for each user, count the number of similar ratings (for our common movies)
            $sql = 'SELECT COUNT(t1.tmdb_id) AS equal_ratios_num
                    FROM user_movies t1 INNER JOIN user_movies t2
                        ON t1.user_id = ' . Auth::user()->id .  
                        ' AND t2.user_id = ' . $user->user_id .
                        ' AND t1.tmdb_id = t2.tmdb_id
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
        $sql =  'SELECT t2.tmdb_id, AVG(t2.ratio) AS avg_ratio
                FROM user_movies t1 INNER JOIN user_movies t2
                    ON t1.user_id = ' . Auth::user()->id . 
                    ' AND t2.user_id IN(' . $arguments . ')
                    AND t2.tmdb_id NOT IN (SELECT tmdb_id
                                            FROM user_movies
                                            WHERE user_id = '. Auth::user()->id .')
                GROUP BY(t2.tmdb_id)
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
                                                'tmdb_id' => $recommended_movie['tmdb_id'],
                                                'avg_ratio' => $recommended_movie['avg_ratio']
                                                ]);
        }                                     
    } // saveRecommendations()




    // get and return the hidden movies
    // #return \Illuminate\Http\Response
    public function getHiddenMovies()
    {
        $hidden_movies = UserMovie::where([
            'user_id' => Auth::user()->id,
            'hidden' => '1',
            'watchlater' => '0'
        ])->get();

        return response()->json([
            'hidden_movies'    => $hidden_movies,
        ], 200);
    }

    // get and return the watch later movies
    // #return \Illuminate\Http\Response
    public function getWatchLaterMovies()
    {
        $later_movies = UserMovie::where([
            'user_id' => Auth::user()->id,
            'hidden' => '0',
            'watchlater' => '1'
        ])->get();

        return response()->json([
            'later_movies'    => $later_movies,
        ], 200);
    }

    // add new movie with rating to DB
    public function rate(Request $request)
    {
        // check if the user has previously rated/hidden/saved the movie
        $userMovie = UserMovie::where([
            'user_id' => Auth::user()->id,
            'tmdb_id' => $request->input("tmdb_id")
        ])->first();

        if($userMovie == null){
            // create new record in user_movies table
            $userMovie = new UserMovie();
            $userMovie->user_id = Auth::user()->id;
            $userMovie->tmdb_id = $request->input("tmdb_id");
            $userMovie->ratio = $request->input("user_rating");
            $userMovie->save();
        } else {
            // change rating for an existing movie record
            $userMovie->ratio = $request->input("user_rating");
            // reset hidden & watchlater flags
            $userMovie->hidden = '0';
            $userMovie->watchlater = '0';
            $userMovie->save();
        }
    }

    // add new movie to watch later to DB
    public function watchlater(Request $request)
    {


        // check if the user has previously rated/hidden/saved the movie
        $userMovie = UserMovie::where([
            'user_id' => Auth::user()->id,
            'tmdb_id' => $request->input("tmdb_id")
        ])->first();

        //if they they do not exist in user_movie table create it
        if($userMovie == null) {
            $userMovie = new UserMovie();
            $userMovie->user_id = Auth::user()->id;
            $userMovie->tmdb_id = $request->input("tmdb_id");
            $userMovie->watchlater = '1';
            $userMovie->save();
        } else {
            $userMovie->hidden = '0';
            $userMovie->watchlater = '1';
            $userMovie->ratio = '0';
            $userMovie->save();
        }
        
    }

        // add new movie to watch later to DB
    public function hide(Request $request)
    {


        // check if the user has previously rated/hidden/saved the movie
        $userMovie = UserMovie::where([
            'user_id' => Auth::user()->id,
            'tmdb_id' => $request->input("tmdb_id")
        ])->first();

        //if they they do not exist in user_movie table create it
        if($userMovie == null) {
            $userMovie = new UserMovie();
            $userMovie->user_id = Auth::user()->id;
            $userMovie->tmdb_id = $request->input("tmdb_id");
            $userMovie->hidden = '1';
            $userMovie->save();
        } else {
            $userMovie->hidden = '1';
            $userMovie->ratio = '0';
            $userMovie->watchlater = '0';
            $userMovie->save();
        }
    }


}
