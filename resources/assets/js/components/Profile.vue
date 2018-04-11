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
                                <h2 class="modal-title">{{movie.title}}
                                    <button type="button" class="modal-close" data-dismiss="modal">
                                        Close
                                    </button>
                                </h2>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-3">

                                    <!-- the drop-down list with for choosing rating -->
                                    <!-- the rating of each film is linked to the corresponding array member -->
                                    <div class="modalActions">

                                        <div class="row btnHolder modalBtnHolder">

                                            <button @click="laterMovie(movie.id)">Save
                                            </button>
                                            <button @click="hideMovie(movie.id)">Hide
                                            </button>
                                        </div>

                                        <div class="row">
                                            <div class="rating modalRating col-md-12">
                                                <!-- rating stars -->
                                                <a v-for="i in 5" @click="addRating(movie.id, i)">★</a>
                                            </div>
                                        </div>

                                    </div>

                                    <img class="modal-image" v-bind:src="image_prefix_url + movie.poster_path">

                                </div>
                                <div class="col-md-9">
                                    <div class="list-group">
                                        <div class="row">
                                            <!-- tagline -->
                                            <div class="list-group-item col-md-6">
                                                <span class="li_header">Tagline: </span>
                                                <p class="li_item"> {{movie.tagline}}</p>
                                            </div>

                                            <!-- countries -->
                                            <div class="list-group-item col-md-6">
                                                <span class="li_header">Countries: </span>
                                                <p class="li_item">
                                                    <!-- loop to display all involved countries -->
                                                    <span v-for="(country, index) in movie.production_countries">
                          {{country.name}}
                          <span v-if="movie.production_countries[index + 1] != null">,</span>
                      </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- genres -->
                                            <div class="list-group-item col-md-6">
                                                <span class="li_header">Genres: </span>
                                                <!-- loop to display all genres -->
                                                <p class="li_item">
                      <span v-for="(genre, index) in movie.genres">
                        {{genre.name}}
                        <span v-if="movie.genres[index + 1] != null">,</span>
                      </span>
                                                </p>
                                            </div>

                                            <!-- movie runtime -->
                                            <div class="list-group-item col-md-6">
                                                <span class="li_header">Runtime: </span>
                                                <p class="li_item">{{movie.runtime}} minutes.</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- movie overview -->
                                            <div class="list-group-item">
                                                <span class="li_header">Overview: </span>
                                                <p class="li_item"> {{movie.overview}} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->


    <div class="row">
        <h2 class="space">Movies You Have Rated:</h2>
        <div class="slider slider-nav">

            <a  v-if="TMDBmovies.length > 0"
              v-for="(movie,i) in TMDBmovies" href="#" class="smSlickItem" >

            <img
                v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id)">

            <!-- the drop-down list with for choosing rating -->
            <!-- the rating of each film is linked to the corresponding array member -->
            <div class="slickActions">

                <div class="row">
                    <div class="rating">
                        <!-- rating stars -->
                        <a v-for="i in 5" @click="addRating(movie.id, i)">★</a>
                    </div>
                </div>
                <div class="row btnHolder">

                    <button @click="laterMovie(movie.id)">Save</button>
                    <button @click="hideMovie(movie.id)">Hide</button>
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
        // poster_path: '',
        // title: '',
        // tagline: '',
        // production_countries: '',
        // genres: '',
        // runtime: '',
        // overview: ''
      },
      // the array to store user settings for each movie received from TMDb
      MyDBmovies: [],
      // the array to store full movie details for each movie received from TMDb
      TMDBmovies: [],
      // TMDb api key
      api_key: '?api_key=a3abe9699d800e588cb2a57107b4179c',
      // TMDb api key url prefix
      api_key_prefix: 'https://api.themoviedb.org/3/movie/',
      // TMDb url prefix for posters
      image_prefix_url: 'http://image.tmdb.org/t/p/w500'
    }
  },
  // functions triggered when Vue object is mounted
  mounted() {
    this.getUserMovies()
  },
  methods: {
    getUserMovies() {

      // get all movies from DB junction table for current user
      axios.get('/usermovies')
        .then(response => {
          // put all received movie objects into array
          this.MyDBmovies = response.data.all_rated_by_user
          // reference to Vue object
          var self = this
          // you can't loop through response object, so put it to the array first
          this.MyDBmovies = response.data.all_rated_by_user
          // loop to obtain full movie details from TMDb for each movie from user list
          for (var i = 0; i < this.MyDBmovies.length; i++) {
            // url query string with movie id
            var url = this.api_key_prefix + this.MyDBmovies[i].movie_id + this.api_key
            // get movie object from TMDb by tmdb_id
            $.getJSON(url)
              .done(function(received_movie) {
                // put the received movie object into array
                self.TMDBmovies.push(received_movie)
              })
          }
        })
    },
    // show selected movie info in modal window
            showMovie(movieId) {
                // url query to find movie by tmdb_id
                var url =
                    "https://api.themoviedb.org/3/movie/" + movieId + this.api_key;
                // reference to Vue object
                var self = this;
                // search movie by tmdb_id
                fetch(url)
                    .then(r => r.json())
                    .then(json => {
                        // put the movie object to local object "movie"
                        self.movie = json;
                    });

                $("#movie_info").modal("show");
            },

            hideMovie(tmdb_id) {
                // HIDE BUTTON HANDLER
                axios.post("/hide", {
                                      tmdb_id: tmdb_id
                                    })

            },
            laterMovie(tmdb_id) {
                // WATCHLATER BUTTON HANDLER

                axios.post("/watchlater", {
                                            tmdb_id: tmdb_id
                                          })
            },

            // ADD OR CHANGE RATING ---------------------------------------------------------
            addRating(tmdb_id, rating) {

                // mirror the rating value (1 => 5, 2 => 4, ...)
                rating = 6 - rating;

                // if the movie has not been rated yet, store new raiting in DB
                if (this.ratings[tmdb_id] === undefined) {

                    axios.post("/store", {
                                         tmdb_id: tmdb_id,
                                         user_rating: rating
                                         })
                    // reflect changes in the local array
                    .then(response => { this.ratings[tmdb_id] = rating });
                }
                // if the rating has changed compared to the previous value, update rating in DB
                else if (this.ratings[tmdb_id] !== rating) {

                    axios.post("/update", {
                                           tmdb_id: tmdb_id,
                                           user_rating: rating
                                          })
                    // reflect changes in the local array
                    .then(response => {this.ratings[tmdb_id] = rating;});
                }
            } // addRating()
  }
}
</script>
