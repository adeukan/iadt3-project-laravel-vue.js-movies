<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                


                <!-- modal window with the selected movie info -->
                <div class="modal fade"
                     tabindex="-1"
                     role="dialog"
                     id="movie_info">
                    <div class="modal-dialog"
                         role="document">
                        <div class="modal-content">
                                <!--
                                <div class="modal-header">
                                <h2 class="modal-title">{{movie.title}}
                                    <button type="button" class="modal-close" data-dismiss="modal">
                                        Close
                                    </button>
                                </h2>
                                </div>
                              -->
                                <div class="col-md-10">
                                    <div class="list-group">
                                        <div class="row">
                                            <div class="list-group-item col-md-12">
                                                <h2 class="modal-title">{{movie.title}} <span v-if="movie.runtime > 0" class="modal_runtime"> | {{movie.runtime}} minutes</span></h2>
                                            </div>
                                            <div class="list-group-item col-md-12 modal_tagline">
                                                <p v-if="movie.tagline != 0" class="li_item modal_tagline">
                                                    {{movie.tagline}}
                                                </p>
                                                <!-- loop to display all production_companies -->
                                                <p v-if="movie.production_companies != 0" class="li_item modal_tagline">
                                                  <span v-for="(company, index) in movie.production_companies">
                                                    {{company.name}}
                                                    <span v-if="movie.production_companies[index + 1] != null">|</span>
                                                  </span>
                                                </p>
                                                <a v-if="movie.homepage != null" class="li_item modal_tagline" v-bind:href="movie.homepage">
                                                    Website Link
                                                </a>
                                            </div>
                                            <!-- countries -->
                                            <div class="list-group-item col-md-12">
                                                <p v-if="movie.release_date != null" class="li_item modal_info">
                                                    {{movie.release_date}}
                                                </p>
                                                <p v-if="movie.production_countries != 0" class="li_item modal_info">
                                                    <!-- loop to display all involved countries -->
                                                    <span v-for="(country, index) in movie.production_countries">
													                            {{country.name}}
													                          <span v-if="movie.production_countries[index + 1] != null">,</span>
											                              </span>
                                                </p>
                                                <!-- loop to display all genres -->
                                                <p v-if="movie.genres != null" class="li_item modal_info">
                            											<span v-for="(genre, index) in movie.genres">
                            												{{genre.name}}
                            												<span v-if="movie.genres[index + 1] != null">|</span>
                            											</span>
                                                </p>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <!-- movie overview -->
                                            <div class="list-group-item">
                                                <p class="li_item li_item_main"> {{movie.overview}} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                  <button type="button" class="modal-close" data-dismiss="modal">
                                      X
                                  </button>
                                </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <div clas="row">
                    <h2>Popular Movies</h2>
                    <div class="slider-parent">
                        <slick ref="popSlick" :options="slickOptions">

                            <a v-if="index < 30" v-for="(movie,index) in popular_movies" href="#" class="smSlickItem">

                                <img v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id,movie.backdrop_path)">

                                <!-- the rating of each film is linked to the corresponding array member -->
                                <div class="slickActions">

                                    <div class="row">
                                        <div class="rating">
                                            <!-- rating stars -->
                                            <a v-for="i in 5" @click="addRating(movie.id, i)">★</a>
                                        </div>
                                        <div v-if="getRating(movie.id) > 0" class="ratingRated">
                                            <!-- get what the movie has been rated -->
                                            <span v-for="i in getRating(movie.id)">★</span>
                                        </div>
                                    </div>
                                    <div class="row btnHolder">

                                        <button @click="laterMovie(movie.id)">Save</button>
                                        <button @click="hideMovie(movie.id)">Hide</button>
                                    </div>
                                </div>
                            </a>

                        </slick>
                    </div>
                </div>

                <div clas="row">
                    <h2 v-if="final_recommendations.length == 0">High Rated Movies:</h2>
                    <h2 v-else>Recommended Movies:</h2>
                    <div class="slider-parent">
                        <slick ref="secondSlick" :options="slickOptions">

                            <a v-if="index < 30"
                           v-for="(movie,index) in second_line_movies" href="#" class="smSlickItem">

                            <img
                                    v-bind:src="image_prefix_url + movie.poster_path" class="slickImage"
                                    @click="showMovie(movie.id,movie.backdrop_path)">

                            <!-- the drop-down list with for choosing rating -->
                            <!-- the rating of each film is linked to the corresponding array member -->
                            <div class="slickActions">

                                <div class="row">
                                    <div class="rating">
                                        <!-- rating stars -->
                                        <a v-for="i in 5" @click="addRating(movie.id, i)">★</a>
                                    </div>
                                    <div v-if="getRating(movie.id) > 0" class="ratingRated">
                                        <!-- get what the movie has been rated -->
                                        <span v-for="i in getRating(movie.id)">★</span>
                                    </div>
                                </div>
                                <div class="row btnHolder">

                                    <button @click="laterMovie(movie.id)">Save</button>
                                    <button @click="hideMovie(movie.id)">Hide</button>
                                </div>
                            </div>
                        </a>

                        </slick>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Slick from "vue-slick";

export default {
  components: {
    Slick
  },
  data() {
    return {
      slickOptions: {
        slidesToShow: 5,
        slidesToScroll: 4,
        responsive: [
          {
            breakpoint: 1300,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      },
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
      // array with user ratings
      ratings: {},
      // contains all popular movies received from TMDb
      popular_movies: [],
      MyDBmovies: [],
      // contains all highest rated movies received from TMDb
      high_rated_movies: [],
      // recommended movies (without )
      recommended_array: [],
      final_recommendations: [],
      second_line_movies: [],
      // tmdb api key value
      api_key: "api_key=a3abe9699d800e588cb2a57107b4179c",
      // url prefix for getting posters
      image_prefix_url: "http://image.tmdb.org/t/p/original"
    };
  },

  // functions triggered when Vue object is mounted
  mounted() {
    // get most popular and highest rated movies to display them in the first line
    this.getPopularMovies(),
    this.getHighRatedMovies(),
    // check DB for previously saved recommendations
    // depending on the checking result, run a function to fill the second line of movies
    this.checkRecommendations();
    this.getUserMovies()
  },
  watch: {    //https://github.com/staskjs/vue-slick/issues/45 -- answer to slick not working
    popular_movies: function (newMovies) {
      let currIndex = this.$refs.popSlick.currentSlide()

      this.$refs.popSlick.destroy()
      this.$nextTick(() => {
        this.$refs.popSlick.create()
        this.$refs.popSlick.goTo(currIndex, true)
      })
    },
    second_line_movies: function (newMovies) {
      let currIndex = this.$refs.secondSlick.currentSlide()

      this.$refs.secondSlick.destroy()
      this.$nextTick(() => {
        this.$refs.secondSlick.create()
        this.$refs.secondSlick.goTo(currIndex, true)
      })
    }
  },

  methods: {
    checkRecommendations() {
      // check DB for previously saved recommendations
      axios.get("/check_recommendations").then(response => {
        // if there are no any previously saved recommendations
        if (response.data.recommended == "nothing") {
          // if "I" have rated more than 50 movies - try to get new recommendations!
          if (response.data.rated > 50) {
            this.getRecommendations();
          } else {
            // or just get the list of highest rated movies
            this.getHighRatedMovies();
            this.useHighRatedMovies();
          }
        } else {
          // if "I" have previously saved recommendations (movies)
          // put them into a local array (create reference)
          this.recommended_array = response.data.recommended;
          // and get full info for each of them
          this.getRecommendedInfo();
        }
      });
    },

    // getting recommendations
    getRecommendations() {
      // first, get the recommendations (list of movies)
      axios.get("/get_recommendations").then(response => {
        // if recommendations are present
        if (response.data.recommended != "nothing") {
          // put them into a local array (create reference)
          this.recommended_array = response.data.recommended;

          // then store them in DB
          axios.post("/save_recommendations", this.recommended_array);

          this.getRecommendedInfo();
        } else {
          this.useHighRatedMovies();
        }
      });
    },

    getRecommendedInfo() {
      this.recommended_array.forEach(movie => {
        // url query to find movie by tmdb_id
        var url =
          "https://api.themoviedb.org/3/movie/" +
          movie.movie_id +
          "?" +
          this.api_key;
        // reference to Vue object
        var self = this;

        fetch(url)
          .then(response => response.json())
          .then(json => {
            // put the movie object to local object "movie"
            self.final_recommendations.push(json);
          })
          .then((this.second_line_movies = this.final_recommendations));
      });
    },

    useHighRatedMovies() {
      this.second_line_movies = this.high_rated_movies;
    },

    getPopularMovies() {
      // url query for all popular movies
      var url =
        "https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&api_key=a3abe9699d800e588cb2a57107b4179c";
      // reference to Vue object
      var self = this;
      // get all popular movies from TMDb
      $.getJSON(url).done(function(received_movies) {
        // put the received movies into array
        self.popular_movies = received_movies.results;
      });
    },

    getHighRatedMovies() {
      // url query for all highest rated movies
      var url =
        "https://api.themoviedb.org/3/discover/movie?sort_by=vote_average.desc&api_key=a3abe9699d800e588cb2a57107b4179c";

      // reference to Vue object
      var self = this;
      // get all highest rated movies from TMDb
      $.getJSON(url).done(function(response) {

        for(var i = 0;i < response.results.length; i++) {
          if(response.results[i].poster_path === null) {
            response.results.splice(i, 1);
          }
        }
        // put the received movies into array
        self.high_rated_movies = response.results;
      });
    },
    getUserMovies() {

        // get all movies from DB junction table for current user
        axios.get('/usermovies')
          .then(response => {
            // put all received movie objects into array
            this.MyDBmovies = response.data.all_rated_by_user
            // reference to Vue object
            var self = this
          })
      },
    getRating(movieId) {
        var self = this
        for(var i = 0; i < this.MyDBmovies.length; i++) {
          if(movieId == this.MyDBmovies[i].movie_id) {
            var rating = this.MyDBmovies[i].ratio;
            return rating;
          }
        }
    },
    // show selected movie info in modal window
    showMovie(movieId,backgroundPath) {
      // url query to find movie by tmdb_id
      var url =
        "https://api.themoviedb.org/3/movie/" + movieId + "?" + this.api_key;
      // reference to Vue object
      var self = this;
      // search movie by tmdb_id
      fetch(url)
        .then(r => r.json())
        .then(json => {
          // put the movie object to local object "movie"
          self.movie = json;

          if(self.movie.release_date != null) {
            var date = self.movie.release_date;
            var newdate = date.split("-").reverse().join("/");
            self.movie.release_date = newdate;
          }
        });

      var urlImage = "http://image.tmdb.org/t/p/";
      if(backgroundPath != null) {
        var backgroundImage = urlImage + "original" + backgroundPath;
        $('.modal').css('background-image', 'url(' + backgroundImage + ')');
      } else {
        $('.modal').css('background', '#636e72');
      }

      $("#movie_info").modal("show");
    },

    hideMovie(tmdb_id) {
      // HIDE BUTTON HANDLER
      axios.post("/hide", {
        tmdb_id: tmdb_id
      });
    },
    laterMovie(tmdb_id) {
      // WATCHLATER BUTTON HANDLER

      axios.post("/watchlater", {
        tmdb_id: tmdb_id
      });
    },

    // ADD OR CHANGE RATING ---------------------------------------------------------
    addRating(tmdb_id, rating) {
      // mirror the rating value (1 => 5, 2 => 4, ...)
      rating = 6 - rating;

      // if the movie has not been rated yet, store new raiting in DB
      if (this.ratings[tmdb_id] === undefined) {
        axios
          .post("/store", {
            tmdb_id: tmdb_id,
            user_rating: rating
          })
          // reflect changes in the local array
          .then(response => {
            this.ratings[tmdb_id] = rating;
          });
      } else if (this.ratings[tmdb_id] !== rating) {
        // if the rating has changed compared to the previous value, update rating in DB
        axios
          .post("/update", {
            tmdb_id: tmdb_id,
            user_rating: rating
          })
          // reflect changes in the local array
          .then(response => {
            this.ratings[tmdb_id] = rating;
          });
      }
    }
  }


};
</script>