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

    // получение пользователей со схожими вкусами
    public function getFriends() {

        // SQL для получения пользователей со схожим набором фильмов
        $sql = 'SELECT t1.user_id, COUNT(t2.movie_id > 50) AS same_movies_num
             FROM user_movies t1 INNER JOIN user_movies t2
                ON t1.user_id != ' . Auth::user()->id . 
                ' AND t2.user_id = ' . Auth::user()->id .
                ' AND t1.movie_id = t2.movie_id
             GROUP BY t1.user_id
             ORDER BY COUNT(t2.movie_id) DESC
             LIMIT 100';   

        // выполнить и вернуть массив объектов, отсортированный по кол-ву общих фильмов
        // каждый объект включает ID пользователя и количество общих фильмов с текущим пользователем
        $same_movie_users = DB::select($sql);

        // массив для пользователей со схожим вкусом
        $friends_array = [];

        // отобрать из полученного массива пользователей с наиболее схожим вкусом
        foreach ($same_movie_users as $user) {
            
            // SQL для получения количества оценок схожих с оценками текущего пользователя
            $sql = 'SELECT COUNT(t1.movie_id) AS equal_ratios_num
                    FROM user_movies t1 INNER JOIN user_movies t2
                        ON t1.user_id = ' . Auth::user()->id .  
                        ' AND t2.user_id = ' . $user->user_id .
                        ' AND t1.movie_id = t2.movie_id
                        AND (t1.ratio - t2.ratio) BETWEEN -1 AND 1';

            // выполнение запроса для каждого пользователя из массива $same_movie_users
            $response = DB::select($sql);

            // коэффициент схожести  = количество фильмов с одинаковой оценкой / общее количество фильмов
            $factor = ($response[0]->equal_ratios_num) / $user->same_movies_num;

            // добавить пользователя в друзья если коэфициент схожести больше 0.7
            if($factor >= 0.7) {

                // создать объект
                $friend = new stdClass();
                // добавить поля ID и коэффициент схожести
                $friend->user_id = $user->user_id;
                $friend->factor = $factor;
                // добавить объект в массив друзей
                array_push($friends_array, $friend);
            }
        }

        // отсортировать пользователей (объекты) в массиве по значению поля factor
        usort($friends_array, function($f1, $f2)
                                {
                                    if ($f1->factor == $f2->factor)
                                        return 0;
                                    
                                    return ($f1->factor > $f2->factor) ? -1 : 1;
                                }
        );

        // вернуть отсортированный массив
        return response()->json([
                'friends_array' => $friends_array,
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
        // таблица movies не нужна, она только усложняет код
        // // create new record in movie table
        // $movie = new Movie();
        // $movie->tmdb_id = $request->input("tmdb_id");
        // $movie->save();

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
