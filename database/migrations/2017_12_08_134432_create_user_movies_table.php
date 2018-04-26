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

            $table->increments('id');
           
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('tmdb_id')->unsigned();
            
            // composite unique value
            $table->unique(['user_id', 'tmdb_id']);

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
