<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

<!-- MODAL   MODAL   MODAL   MODAL   MODAL   MODAL   MODAL   MODAL   MODAL   MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="movie_info">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="col-md-10">
                <div class="list-group">
                    <div class="row">
                        <!-- TITLE TITLE TITLE -->
                        <div class="list-group-item col-md-12">
                            <h2 class="modal-title">{{movie.title}} <span v-if="movie.runtime > 0" class="modal_runtime"> | {{movie.runtime}} minutes</span></h2>
                        </div>
                        <!-- TAGLINE TAGLINE TAGLINE -->
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
                        <!-- INFO INFO INFO -->
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
                        <!-- INFO INFO INFO -->
                    </div>
                    <!-- PLOT PLOT PLOT -->
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
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

                <!-- LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1  -->
                <div class="row slide1" ref="popSlider">
                    <h2 class="carousel-header">Popular Movies</h2>
                    <div class="slider-parent">
                        <slick ref="popSlick" :options="slickOptions">
                          <a id="popSlick" v-if="index < 30" v-for="(movie,index) in popular_movies" v-bind:id="index" href="#" :key="movie.id" class="smSlickItem">

                              <img v-bind:src="image_prefix_url + movie.poster_path" class="slickImage"
                                   @click="showMovie(movie.id,movie.backdrop_path)">

                              <div class="slickActions">
                                  <div class="row">
                                      <div class="rating">
                                        <star-rating
                                                @rating-selected="setRating($event, movie.id)"
                                                inactive-color="#636e72"
                                                active-color="#dfe6e9"
                                                :star-size="25"
                                                :show-rating="false">
                                        </star-rating>
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

                <hr>

                <!-- LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2  -->
                <div class="row slide2" ref="secondSlider">
                    <h2 v-if="recommended_display.length == 0" class="carousel-header">High Rated Movies</h2>
                    <h2 v-else class="carousel-header">Recommended Movies</h2>
                    <div class="slider-parent">
                        <slick ref="secondSlick" :options="slickOptions">

                            <a v-if="index < 30"
                           v-for="(movie,index) in second_line_movies" href="#" id="movie.id" class="smSlickItem">

                            <img
                                    v-bind:src="image_prefix_url + movie.poster_path" class="slickImage"
                                    @click="showMovie(movie.id,movie.backdrop_path)">

                            <!-- the rating of each film is linked to the corresponding array member -->
                            <div class="slickActions">

                                <div class="row">
                                    <div class="rating">
                                      <star-rating
                                              @rating-selected="setRating($event, movie.id)"
                                              inactive-color="#636e72"
                                              active-color="#dfe6e9"
                                              :star-size="25"
                                              :show-rating="false">
                                      </star-rating>
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
import StarRating from "vue-star-rating";

export default {
  components: {
    Slick,
    StarRating
  },
  data() {
    return {
      rating: 0,
      id: 0,
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
      movie: {},
      // array with user ratings
      ratings: {},
      // user movies
      my_movies: [],
      // popular movies with full info
      popular_movies: [],
      // recommended movies without full info
      recommended: [],
      // recommended movies with full info
      recommended_display: [],
      // movies to display in the second line
      second_line_movies: [],
      // tmdb api key value
      api_key: "api_key=a3abe9699d800e588cb2a57107b4179c",
      // url prefix for getting posters
      image_prefix_url: "http://image.tmdb.org/t/p/original"
    };
  },

  // functions triggered when Vue object is mounted
  mounted() {
    // get the list of "my" movies and then start next method
    this.getMyMovies();
  },

  watch: {
    // https://github.com/staskjs/vue-slick/issues/45 -- answer to slick not working

    second_line_movies: function(newMovies) {
      let currIndex = this.$refs.secondSlick.currentSlide();

      this.$refs.secondSlick.destroy();
      this.$nextTick(() => {
        this.$refs.secondSlick.create();
        this.$refs.secondSlick.goTo(currIndex, true);
      });
    },

    popular_movies: function(newMovies) {
      let currIndex = this.$refs.popSlick.currentSlide();

      this.$refs.popSlick.destroy();
      this.$nextTick(() => {
        this.$refs.popSlick.create();
        this.$refs.popSlick.goTo(currIndex, true);
      });
    },
    rating: function(newRating) {
          axios.post("/rate", {
              tmdb_id: this.id,
              user_rating: this.rating
      })
    }
  },

  methods: {

    // ------------------------------------------------------------------------------------------
    setRating: function(rating, id){
      this.rating = rating;
      this.id = id;
    },


    // ------------------------------------------------------------------------------------------
    getMyMovies() {
      axios.get("/my_movies").then(response => {
        // if there is any response
        if (response !== undefined) {
          // loop through the response data
          response.data.my_movies.forEach(movie => {
            // and put ID of each my movie to the array
            this.my_movies.push(movie.tmdb_id);
          });
        }
        // get the list of most popular movies, remove "my" movies from the list, display movies in the first line
        this.getPopularMovies();
        // check previously saved recommendations in DB, and call next method
        this.checkRecommendations();
      });
    },

    // ------------------------------------------------------------------------------------------ 
    getPopularMovies() {
       // reference to Vue object
      var self = this;

      // url query for all popular movies
      for(var i = 1; i < 5; i++) {
        // url query for all popular movies
        var url = "https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&include_adult=false&page=" + i + "&api_key=a3abe9699d800e588cb2a57107b4179c";

        // get all popular movies from TMDb
        $.getJSON(url).done(function(response) {
          // get rid of movies that have already been marked by "my"
          for (var i = 0; i < response.results.length; i++) {
            // get rid of the movies which are already in "my_movies" list
            if (self.my_movies.indexOf(response.results[i].id) !== -1) {
              // delete only the value, reindexing the array now may destroy the loop
              delete response.results[i];
            }
            // get rid of movies without a poster
            else if (response.results[i].poster_path === null) {
              // delete only the value, reindexing the array now may destroy the loop
              delete response.results[i];
            }
          }
          // delete undefined members and reindex the array
          // use these movies to display them in the first line
          self.popular_movies.push.apply(self.popular_movies, response.results.filter(member => member !== undefined));
        });
      }
    },


    // ------------------------------------------------------------------------------------------
    getHighRatedMovies() {
      // reference to Vue object
      var self = this;
      for(var i = 1; i < 5; i++) {
        // url query for all highest rated movies
        var url = "https://api.themoviedb.org/3/discover/movie?api_key=a3abe9699d800e588cb2a57107b4179c&language=en-US&sort_by=vote_count.desc&include_adult=false&include_video=false&page=" + i;

        // get all highest rated movies from TMDb
        $.getJSON(url).done(function(response) {
      
          // get rid of movies that have already been marked by "my"
          for (var i = 0; i < response.results.length; i++) {
            // get rid of the movies which are already in "my_movies" list
            if (self.my_movies.indexOf(response.results[i].id) !== -1) {
              // delete only the value, reindexing the array now may destroy the loop
              delete response.results[i];
            }
            // get rid of movies without a poster
            else if (response.results[i].poster_path === null) {
              // delete only the value, reindexing the array now may destroy the loop
              delete response.results[i];
            }
          }
          // delete undefined members and reindex the array
          // use these movies to display them in the second line
          self.second_line_movies.push.apply(self.second_line_movies, response.results.filter(member => member !== undefined));
        });
      }
    },


    // ------------------------------------------------------------------------------------------
    checkRecommendations() {
      // check DB for previously saved recommendations
      axios.get("/check_recommendations").then(response => {
        // if there are no any previously saved recommendations
        if (response.data.recommended === "nothing") {

          // if "I" have rated more than 50 movies - try to get new recommendations!
          if (response.data.rated > 50) {
            this.getRecommendations();
          } else {
            // or just get the list of highest rated movies
            this.getHighRatedMovies();
          }
        } else {
          // if "I" have previously saved recommendations (movies)
          // put them into a local array (create reference)
          this.recommended = response.data.recommended;
          // and get full info for each of them
          this.getRecommendedInfo();
        }
      });
    },

    // ------------------------------------------------------------------------------------------
    getRecommendations() {
      // first, get the recommendations (list of movies)
      axios.get("/get_recommendations").then(response => {
        // if recommendations are present
        if (response.data.recommended !== "nothing") {
          // put them into a local array (create reference)
          this.recommended = response.data.recommended;
          // then store them in DB
          axios.post("/save_recommendations", this.recommended);
          // and get full info for each of them
          this.getRecommendedInfo();
        } else {
          // if recommendations can't be generated, use highest rated movies instead
          this.getHighRatedMovies();
        }
      });
    },


    // get full info for each of recommended movies ---------------------------------------------
    getRecommendedInfo() {

      // we can't make all requests at once, it gives error 429 - to many requests to TMDB
      var recommended_part = this.recommended.slice(0, 20);

      recommended_part.forEach(movie => {
        // url query to find movie by tmdb_id
        var url = "https://api.themoviedb.org/3/movie/" + movie.tmdb_id + "?" + this.api_key;
        // reference to Vue object
        var self = this;
        // make request for each next movie
        fetch(url)
          .then(response => response.json())
          .then(json => {
            // put the movie object to local object "movie"
            self.recommended_display.push(json);
          })
          // set the second line to show recommended movies 
          .then((this.second_line_movies = this.recommended_display));
      });
    },

    // show selected movie info in modal window ------------------------------------------------
    showMovie(tmdb_id, backgroundPath) {
      // url query to find movie by tmdb_id
      var url =
        "https://api.themoviedb.org/3/movie/" + tmdb_id + "?" + this.api_key;
      // reference to Vue object
      var self = this;
      // search movie by tmdb_id
      fetch(url)
        .then(r => r.json())
        .then(json => {
          // local reference to movie object
          self.movie = json;

          if (self.movie.release_date != null) {
            var date = self.movie.release_date;
            var newdate = date
              .split("-")
              .reverse()
              .join("/");
            self.movie.release_date = newdate;
          }
        });

      var urlImage = "http://image.tmdb.org/t/p/";
      if (backgroundPath != null) {
        var backgroundImage = urlImage + "w1280" + backgroundPath;
        $(".modal").css("background-image", "url(" + backgroundImage + ")");
      } else {
        $(".modal").css("background", "#636e72");
      }

      $("#movie_info").modal("show");
    },


    // ------------------------------------------------------------------------------------------
    hideMovie(tmdb_id) {
      var spliceThis = 5;

      for(var i = 0; i < this.popular_movies.length; i++) {

        if(tmdb_id === this.popular_movies[i].id) {
          var styleChange = document.getElementById(i);
          styleChange.classList.add("fadeTransition");

          spliceThis = i;

          setTimeout(this.spliceFun(this.popular_movies,spliceThis), 4000);

        }
      }
      /*
      axios.post("/hide", {
        tmdb_id: tmdb_id
      });
      */
    },

    spliceFun(array,spliceThis) {
      array.splice(spliceThis, 1);
    },

    // ------------------------------------------------------------------------------------------
    laterMovie(tmdb_id) {
      axios.post("/watchlater", {
        tmdb_id: tmdb_id
      });
    },

  }
};
</script>