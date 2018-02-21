<?php

use Illuminate\Database\Seeder;
use App\Project;
use App\UserMovie;
use App\Movie;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // seed the projects table by sample data
        $this->call(ProjectsTableSeeder::class);
    }
}

class ProjectsTableSeeder extends Seeder {

  public function run()
  {
    DB::table('movies')->delete();
    DB::disableQueryLog();

    Movie::create([
      'tmdb_id' => 10329,
  ]);
Movie::create([
      'tmdb_id' => 103380,
  ]);
Movie::create([
      'tmdb_id' => 11169,
  ]);
Movie::create([
      'tmdb_id' => 11544,
  ]);
Movie::create([
      'tmdb_id' => 11575,
  ]);
Movie::create([
      'tmdb_id' => 11863,
  ]);
Movie::create([
      'tmdb_id' => 124581,
  ]);
Movie::create([
      'tmdb_id' => 12502,
  ]);
Movie::create([
      'tmdb_id' => 131475,
  ]);
Movie::create([
      'tmdb_id' => 132169,
  ]);
Movie::create([
      'tmdb_id' => 13382,
  ]);
Movie::create([
      'tmdb_id' => 1369,
  ]);
Movie::create([
      'tmdb_id' => 13701,
  ]);
Movie::create([
      'tmdb_id' => 141052,
  ]);
Movie::create([
      'tmdb_id' => 14108,
  ]);
Movie::create([
      'tmdb_id' => 14435,
  ]);
Movie::create([
      'tmdb_id' => 151647,
  ]);
Movie::create([
      'tmdb_id' => 157827,
  ]);
Movie::create([
      'tmdb_id' => 160019,
  ]);
Movie::create([
      'tmdb_id' => 17139,
  ]);
Movie::create([
      'tmdb_id' => 17335,
  ]);
Movie::create([
      'tmdb_id' => 175464,
  ]);
Movie::create([
      'tmdb_id' => 17673,
  ]);
Movie::create([
      'tmdb_id' => 181735,
  ]);
Movie::create([
      'tmdb_id' => 185191,
  ]);
Movie::create([
      'tmdb_id' => 18649,
  ]);
Movie::create([
      'tmdb_id' => 18948,
  ]);
Movie::create([
      'tmdb_id' => 201371,
  ]);
Movie::create([
      'tmdb_id' => 20210,
  ]);
Movie::create([
      'tmdb_id' => 20304,
  ]);
Movie::create([
      'tmdb_id' => 205361,
  ]);
Movie::create([
      'tmdb_id' => 20674,
  ]);
Movie::create([
      'tmdb_id' => 20682,
  ]);
Movie::create([
      'tmdb_id' => 21610,
  ]);
Movie::create([
      'tmdb_id' => 216212,
  ]);
Movie::create([
      'tmdb_id' => 24004,
  ]);
Movie::create([
      'tmdb_id' => 24512,
  ]);
Movie::create([
      'tmdb_id' => 246917,
  ]);
Movie::create([
      'tmdb_id' => 247,
  ]);
Movie::create([
      'tmdb_id' => 26230,
  ]);
Movie::create([
      'tmdb_id' => 26347,
  ]);
Movie::create([
      'tmdb_id' => 271499,
  ]);
Movie::create([
      'tmdb_id' => 274766,
  ]);
Movie::create([
      'tmdb_id' => 275953,
  ]);
Movie::create([
      'tmdb_id' => 281292,
  ]);
Movie::create([
      'tmdb_id' => 284021,
  ]);
Movie::create([
      'tmdb_id' => 287845,
  ]);
Movie::create([
      'tmdb_id' => 292082,
  ]);
Movie::create([
      'tmdb_id' => 308332,
  ]);
Movie::create([
      'tmdb_id' => 312070,
  ]);
Movie::create([
      'tmdb_id' => 31342,
  ]);
Movie::create([
      'tmdb_id' => 318357,
  ]);
Movie::create([
      'tmdb_id' => 32256,
  ]);
Movie::create([
      'tmdb_id' => 32261,
  ]);
Movie::create([
      'tmdb_id' => 32499,
  ]);
Movie::create([
      'tmdb_id' => 33209,
  ]);
Movie::create([
      'tmdb_id' => 33253,
  ]);
Movie::create([
      'tmdb_id' => 34125,
  ]);
Movie::create([
      'tmdb_id' => 352978,
  ]);
Movie::create([
      'tmdb_id' => 3542,
  ]);
Movie::create([
      'tmdb_id' => 35463,
  ]);
Movie::create([
      'tmdb_id' => 35751,
  ]);
Movie::create([
      'tmdb_id' => 35755,
  ]);
Movie::create([
      'tmdb_id' => 3640,
  ]);
Movie::create([
      'tmdb_id' => 37431,
  ]);
Movie::create([
      'tmdb_id' => 37588,
  ]);
Movie::create([
      'tmdb_id' => 38361,
  ]);
Movie::create([
      'tmdb_id' => 39443,
  ]);
Movie::create([
      'tmdb_id' => 398169,
  ]);
Movie::create([
      'tmdb_id' => 408952,
  ]);
Movie::create([
      'tmdb_id' => 40924,
  ]);
Movie::create([
      'tmdb_id' => 415086,
  ]);
Movie::create([
      'tmdb_id' => 416477,
  ]);
Movie::create([
      'tmdb_id' => 42178,
  ]);
Movie::create([
      'tmdb_id' => 43239,
  ]);
Movie::create([
      'tmdb_id' => 43323,
  ]);
Movie::create([
      'tmdb_id' => 4368,
  ]);
Movie::create([
      'tmdb_id' => 437,
  ]);
Movie::create([
      'tmdb_id' => 44852,
  ]);
Movie::create([
      'tmdb_id' => 44890,
  ]);
Movie::create([
      'tmdb_id' => 45317,
  ]);
Movie::create([
      'tmdb_id' => 45497,
  ]);
Movie::create([
      'tmdb_id' => 464810,
  ]);
Movie::create([
      'tmdb_id' => 472983,
  ]);
Movie::create([
      'tmdb_id' => 47907,
  ]);
Movie::create([
      'tmdb_id' => 49609,
  ]);
Movie::create([
      'tmdb_id' => 54307,
  ]);
Movie::create([
      'tmdb_id' => 56298,
  ]);
Movie::create([
      'tmdb_id' => 58306,
  ]);
Movie::create([
      'tmdb_id' => 59387,
  ]);
Movie::create([
      'tmdb_id' => 65513,
  ]);
Movie::create([
      'tmdb_id' => 6964,
  ]);
Movie::create([
      'tmdb_id' => 7548,
  ]);
Movie::create([
      'tmdb_id' => 76521,
  ]);
Movie::create([
      'tmdb_id' => 79545,
  ]);
Movie::create([
      'tmdb_id' => 80218,
  ]);
Movie::create([
      'tmdb_id' => 8440,
  ]);
Movie::create([
      'tmdb_id' => 84576,
  ]);
Movie::create([
      'tmdb_id' => 85778,
  ]);
Movie::create([
      'tmdb_id' => 85831,
  ]);
Movie::create([
      'tmdb_id' => 8840,
  ]);
Movie::create([
      'tmdb_id' => 88910,
  ]);
Movie::create([
      'tmdb_id' => 9102,
  ]);
Movie::create([
      'tmdb_id' => 9279,
  ]);
Movie::create([
      'tmdb_id' => 9721,
  ]);
  }
}