<template>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <!-- modal window with the selected movie info -->
      <div class="modal modal-lg fade"
           tabindex="-1"
           role="dialog"
           id="movie_info">
        <div class="modal-dialog"
             role="document">
          <div class="modal-content">
            <div class="modal-header">
                  <h2 class="modal-title">Title: {{movie.title}} </h2>
                  <button type="button" class="modal-close" data-dismiss="modal">
                  </button>
            </div>
            <div class="modal-body">
              <img class="modalImg" v-bind:src="image_prefix_url + movie.poster_path">
                <ul class="list-group">
                  <!-- tagline -->
                  <li class="list-group-item"><span class="li_header">Tagline: </span> {{movie.tagline}}</li>
                  <!-- countries -->
                  <li class="list-group-item"><span class="li_header">Countries: </span>
                    <!-- loop to display all involved countries -->
                    <span v-for="(country, index) in movie.production_countries">
    										{{country.name}}
    										<span v-if="movie.production_countries[index + 1] != null">,</span>
                    </span>
                    <!-- genres -->
                    <li class="list-group-item"><span class="li_header">Genres: </span>
                      <!-- loop to display all genres -->
                      <span v-for="(genre, index) in movie.genres">
    										{{genre.name}}
    										<span v-if="movie.genres[index + 1] != null">,</span>
                      </span>
                    </li>
                    <!-- movie runtime -->
                    <li class="list-group-item"><span class="li_header">Runtime: </span> {{movie.runtime}} min</li>
                    <!-- movie overview -->
                    <li class="list-group-item"><span class="li_header">Overview: </span> {{movie.overview}}</li>
                </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    </div>
            
      <div class="row">
        <h2 class="space">Popular Movies:</h2>
        <div class="slider slider-nav">

            <a  v-if="index < 30"
              v-for="(movie,index) in popular_movies" href="#" class="smSlickItem" >

            <img
                v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id)">

              <!-- the drop-down list with for choosing rating -->
              <!-- the rating of each film is linked to the corresponding array member -->
            <div class="slickActions">

              <div class="row">
                <div class="rating" v-model="new_ratings[index]">
                  <!-- scores from 0 to 10 -->
                  <a v-for="i in 10" @click="addRating(movie.id, i)">★</a>
                </div>
              </div>
              <div class="row btnHolder">
                
                <button @click="laterMovie(movie.id)"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                <button @click="hideMovie(movie.id)"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></button>
              </div>

            </div>

          </a>

        </div>
      </div>
      <div class="row">
        <h2 class="noSpace">High Rated Movies:</h2>
        <div class="slider slider-nav">

            <a  v-if="index < 30"
              v-for="(movie,index) in high_rated_movies" href="#" class="smSlickItem" >

            <img
                v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id)">

              <!-- the drop-down list with for choosing rating -->
              <!-- the rating of each film is linked to the corresponding array member -->
            <div class="slickActions">

              <div class="row">
                <div class="rating" v-model="new_ratings[index]">
                  <!-- scores from 0 to 10 -->
                  <a v-for="i in 10" @click="addRating(movie.id, i)">★</a>
                </div>
              </div>
              <div class="row btnHolder">
                <button @click="hideMovie(movie.id)"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></button>
                <button @click="laterMovie(movie.id)"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
              </div>

            </div>

          </a>

        </div>
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
      // contains all popular movies received from TMDb
      popular_movies: [],
      // contains all highest rated movies received from TMDb
      high_rated_movies: [],
      // tmdb api key value
      api_key: 'api_key=a3abe9699d800e588cb2a57107b4179c',
      // url prefix for getting posters
      image_prefix_url: 'http://image.tmdb.org/t/p/w185',
      // массив пользователей со схожими вкусами, отсортирован по степени схожести
      friends_array: []
    }
  },

  // functions triggered when Vue object is mounted
  mounted() {

    this.getFriends(),
    this.getPopularMovies(),
    this.getHighRatedMovies()
  },

  methods: {
  	// получение пользователей со схожими вкусами
    getFriends() {

		axios.get('/get_friends').then(response => {

			this.friends_array = response.data.friends_array
		})

    },

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

    getHighRatedMovies() {

      // url query for all highest rated movies
      var url = 'https://api.themoviedb.org/3/discover/movie?sort_by=vote_average.desc&api_key=a3abe9699d800e588cb2a57107b4179c'

      // reference to Vue object
      var self = this
      // get all highest rated movies from TMDb
      $.getJSON(url)
        .done(function(response) {
          // put the received movies into array
          self.high_rated_movies = response.results
        })
    },

    // show selected movie info in modal window
    showMovie(movieId) {
      // url query to find movie by tmdb_id
      var url = 'https://api.themoviedb.org/3/movie/' + movieId + '?' + this.api_key
      // reference to Vue object
      var self = this
      // search movie by tmdb_id
      fetch(url)
        .then(r => r.json())
        .then(json => {
          self.movie=json;
      });

      $('#movie_info').modal('show')
    },
    hideMovie(movieId) {
      // HIDE BUTTON HANDLER
    }

  },

  // function triggered when changing specified variables
  watch: {
    // reaction to a change in the rating of any film
    new_ratings: function(new_ratings) {

      // loop through all the values from the array with the updated ratings
      for (var i = 0; i < this.new_ratings.length; ++i) {
        // if the current element of the array has changed compared to the old value
        if (this.new_ratings[i] !== this.old_ratings[i]) {

          // // show tmdb_id of the movie which changed rating
          // console.log(this.popular_movies[i].id);
          // // show the new rating
          // console.log(new_ratings[i]);

          // if the previous rating is 0 or null, create new record
          if (this.old_ratings[i] === 0 || this.old_ratings[i] === null) {
            axios.post('/store', {
              'tmdb_id': this.popular_movies[i].id,
              'user_rating': this.new_ratings[i]
            })
          }

          // DOESN'T WORK
          // if the previous rating is not 0 or null, update existing record
          else {
            axios.post('/update', {
              'tmdb_id': this.popular_movies[i].id,
              'user_rating': this.new_ratings[i]
            }).then(function() {
              this.test = 'works'
            })
          }

          // commit changes to the array with old values after DB updating (used for comparison)
          this.old_ratings[i] = new_ratings[i]

        } // end if
      } // end loop
    } // end function
  }, // end watch
}

</script>
