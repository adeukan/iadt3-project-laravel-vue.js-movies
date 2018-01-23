<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMoviesTable extends Migration
{
    // Run the migrations
    // #return void
    public function up()
    {
        Schema::create('user_movies', function (Blueprint $table) {
           
            
            $table->increments('id')->unsigned();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('tmdb_id')->on('movies');

            // $table->enum('rating', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->default(0);

            // temp
            $table->tinyInteger('ratio');

            $table->enum('hidden', [0, 1])->default(0);
            $table->enum('watchlater', [0, 1])->default(0);
            $table->timestamps();



        });
    }

    // Reverse the migrations
    // #return void
    public function down()
    {
        Schema::dropIfExists('user_movies');
    }
}
