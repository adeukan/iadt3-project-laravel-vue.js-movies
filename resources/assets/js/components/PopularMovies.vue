<template>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          Most Popular:
        </div>
        <!-- popular movies table -->
        <div class="panel-body">

              <div class="row">
                <div class="slider slider-nav">

                  <a  v-if="index < 5"
                    v-for="(movie,index) in popular_movies" href="#" class="smSlickItem" >

                    <img @click="showMovie(movie.id)"
                      v-bind:src="image_prefix_url + movie.poster_path" class="slickImage">

                    <!-- the drop-down list with for choosing rating -->
                    <!-- the rating of each film is linked to the corresponding array member -->
                    <select v-model="new_ratings[index]">
                      <!-- scores from 0 to 10 -->
                      <option v-for="i in 11">{{i-1}}</option>
                    </select>

                  </a>

                </div>
              </div>

        </div>

        <!-- modal window with the selected movie info -->
        <div class="modal fade"
             tabindex="-1"
             role="dialog"
             id="movie_info">
          <div class="modal-dialog"
               role="document">
            <div class="modal-content">
              <ul>
                <!-- poster -->
                <img v-bind:src="image_prefix_url + movie.poster_path">
                <!-- title -->
                <li><span class="li_header">Title: </span> {{movie.title}}</li>
                <!-- tagline -->
                <li><span class="li_header">Tagline: </span> {{movie.tagline}}</li>
                <!-- countries -->
                <li><span class="li_header">Countries: </span>
                <!-- loop to display all involved countries -->
                <li><span v-for="(country, index) in movie.production_countries">
											{{country.name}}
											<span v-if="movie.production_countries[index + 1] != null">,</span>
                    </span>
                </li>
                <!-- genres -->
                <li><span class="li_header">Genres: </span>
                  <!-- loop to display all genres -->
                  <span v-for="(genre, index) in movie.genres">
											{{genre.name}}
											<span v-if="movie.genres[index + 1] != null">,</span>
                  </span>
                </li>
                <!-- movie runtime -->
                <li><span class="li_header">Runtime: </span> {{movie.runtime}} min</li>
                <!-- movie overview -->
                <li><span class="li_header">Overview: </span> {{movie.overview}}</li>
              </ul>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      </div>
    </div>
  </div>
</div>

</template>

<script>
export default {

  data() {
    return {
      // temporary stores the properties of selected movie, used to display it in a modal window
      movie: {
        // you must not specify property names explicitly
        // title: '',
        // tagline: '',
        // production_countries: '',
        // genres: '',
        // runtime: '',
        // overview: '',
        // poster_path: ''
      },
      // array with user ratings, any rating change first happens in it
      new_ratings: [],
      // array with the previous user ratings, used for comparison 
      old_ratings: [],
      // contains all popular movies received on api request to TMDb
      popular_movies: [],
      // tmdb api key value
      api_key: 'api_key=a3abe9699d800e588cb2a57107b4179c',
      // url prefix for getting posters
      image_prefix_url: 'http://image.tmdb.org/t/p/w185',

      test: 'hello',
    }
  },
  // functions triggered when Vue object is mounted
  mounted() {
    this.getPopularMovies()
  },
  methods: {
    getPopularMovies() {
      // url query for all popular movies
      var url = 'https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&api_key=a3abe9699d800e588cb2a57107b4179c'
      // reference to Vue object
      var self = this
      // get all popular movies from TMDb
      $.getJSON(url)
        .done(function(received_movies) {
          // put the received movies into array
          self.popular_movies = received_movies.results
        })
    },
    // show selected movie info in modal window
    showMovie(movieId) {
      // url query to find movie by tmdb_id
      var url = 'https://api.themoviedb.org/3/movie/' + movieId + '?' + this.api_key
      // reference to Vue object
      var self = this
      // search movie by tmdb_id
      $.getJSON(url)
        .done(function(received_movie) {
          // put the received movie into object (temporary container)
          self.movie = received_movie
          // show a modal window with information about the selected movie
          $('#movie_info').modal('show')
        })
    }
  },
  // function triggered when changing specified variables
  watch: {
    // reaction to a change in the rating of any film
    new_ratings: function(newRatings) {

      // loop through all the values from the array with the updated ratings
      for (var i = 0; i < newRatings.length; ++i) {
        // if the current element of the array has changed compared to the old value
        if (newRatings[i] !== this.old_ratings[i]) {

          // // show tmdb_id of the movie which changed rating
          // console.log(this.popular_movies[i].id);
          // // show the new rating
          // console.log(newRatings[i]);
          
          // if the previous rating is 0 or null, create new record
          if(this.old_ratings[i] == 0 || this.old_ratings[i] == null)
          {
            axios.post('/store', {
              a: this.popular_movies[i].id,
              b: newRatings[i]
            })
          }
          // DOESN'T WORK
          // if the previous rating is not 0 or null, update existing record
          else
          {
            axios.post('/update', {
              a: this.popular_movies[i].id,
              b: newRatings[i]
            }).then(function() {
              this.test = "works"
            });
          }

          // commit changes to the array with old values after DB updating (used for comparison)
          this.old_ratings[i] = newRatings[i];

        } // end if
      }   // end loop
    }     // end function
  },      // end watch
}
</script>
