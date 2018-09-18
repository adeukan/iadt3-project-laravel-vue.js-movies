<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


// // Auth::routes() includes the following routes:

// Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout');

// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm');
// Route::post('register', 'Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/my_user', 'UserMovieController@getMyUser');
Route::get('/my_movies', 'UserMovieController@getMyMovies');
Route::get('/get_watchlater', 'UserMovieController@getWatchLaterMovies');
Route::get('/get_hidden', 'UserMovieController@getHiddenMovies');
Route::get('/get_rated', 'UserMovieController@getRatedMovies');

Route::get('/check_recommendations', 'UserMovieController@checkRecommendations');
Route::get('/get_recommendations', 'UserMovieController@getRecommendations');
Route::post('/save_recommendations', 'UserMovieController@saveRecommendations');

Route::post('/rate', 'UserMovieController@rate');
Route::post('/hide', 'UserMovieController@hide');
Route::post('/watchlater', 'UserMovieController@watchlater');



// return the home page on any type of request
// Route::get('/{any}', function () {
// 	return view('home');
// })->where(['any' => '.*']);

// use HomeController index function on any type of request
// we can't skip HomeController because it checks the authorisation
Route::get('/{any}', 'HomeController@index')->where(['any' => '.*']);