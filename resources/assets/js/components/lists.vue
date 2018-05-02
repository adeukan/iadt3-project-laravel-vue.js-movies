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
                        </div><!-- modal-content -->
                    </div><!-- modal-dialog -->
                </div><!-- modal -->

				<!-- LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1   LINE_1 -->
				<div class="row">
					<h2 class="carousel-header">Saved Movies</h2>
					<div class="slider-parent">
						<slick ref="saveSlick" :options="slickOptions">

							<a  v-if="later_movies_display.length > 0"
							  v-for="(movie,i) in later_movies_display" href="#" class="smSlickItem" >

							<img
								v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id,movie.backdrop_path)">

							<!-- the rating of each film is linked to the corresponding array member -->
							<div class="slickActions">

								<div class="row">
									<div class="rating">
										<!-- rating stars -->
										<a v-for="i in 5" @click="rateMovie(movie.id, i)">★</a>
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

				<!-- LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2   LINE_2 -->
				<div class="row">
					<h2 class="carousel-header">Hidden Movies</h2>
					<slick ref="hideSlick" :options="slickOptions">

						<a  v-if="hidden_movies_display.length > 0"
						  v-for="(movie,i) in hidden_movies_display" href="#" class="smSlickItem" >

						<img
							v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id,movie.backdrop_path)">

						<!-- the drop-down list with for choosing rating -->
						<!-- the rating of each film is linked to the corresponding array member -->
						<div class="slickActions">

							<div class="row">
								<div class="rating">
									<!-- rating stars -->
									<a v-for="i in 5" @click="rateMovie(movie.id, i)">★</a>
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
		dots: false,
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
	  // 'watchlater' movies without full info
	  later_movies: [],
	  // 'watchlater' movies with full info
	  later_movies_display: [],
	  // 'hidden' movies without full info
	  hidden_movies: [],
	  // 'hidden' movies without full info
	  hidden_movies_display: [],
	  // tmdb api key value
	  api_key: "api_key=a3abe9699d800e588cb2a57107b4179c",

	  // TMDb api key url prefix
	  api_key_prefix: "https://api.themoviedb.org/3/movie/",
	  // url prefix for getting posters
	  image_prefix_url: "http://image.tmdb.org/t/p/w500"
	};
  },

  // ------------------------------------------------------------------------------------------
  watch: {

	//https://github.com/staskjs/vue-slick/issues/45 -- answer to slick not working
	later_movies_display: function(newMovies) {
	  let currIndex = this.$refs.saveSlick.currentSlide();

	  this.$refs.saveSlick.destroy();
	  this.$nextTick(() => {
		this.$refs.saveSlick.create();
		this.$refs.saveSlick.goTo(currIndex, true);
	  });
	},

	hidden_movies_display: function(newMovies) {
	  let currIndex = this.$refs.hideSlick.currentSlide();

	  this.$refs.hideSlick.destroy();
	  this.$nextTick(() => {
		this.$refs.hideSlick.create();
		this.$refs.hideSlick.goTo(currIndex, true);
	  });
	}
  },

  // ------------------------------------------------------------------------------------------
  mounted() {
	// get most popular and highest rated movies to display them in the first line
	this.getWatchLaterMovies(), this.getHiddenMovies();
  },

  methods: {

  // ------------------------------------------------------------------------------------------
	getWatchLaterMovies() {
	  // get all movies from DB junction table for current user
	  axios.get("/get_watchlater").then(response => {
		// put all received movie objects into array
		this.later_movies = response.data.later_movies;
		// reference to Vue object
		var self = this;
		// loop to obtain full movie details from TMDb for each movie from user list
		for (var i = 0; i < this.later_movies.length; i++) {
		  // url query string with movie id
		  var url =
			this.api_key_prefix + this.later_movies[i].tmdb_id + "?" + this.api_key;
		  // get movie object from TMDb by tmdb_id
		  $.getJSON(url).done(function(response) {
				// put the received movie object into array
				self.later_movies_display.push(response);
		  });
		}
	  });
	},


	// ------------------------------------------------------------------------------------------
	getHiddenMovies() {
	  // get all movies from DB junction table for current user
	  axios.get("/get_hidden").then(response => {
		// put all received movie objects into array
		this.hidden_movies = response.data.hidden_movies;
		// reference to Vue object
		var self = this;
		// loop to obtain full movie details from TMDb for each movie from user list
		for (var i = 0; i < this.hidden_movies.length; i++) {
		  // url query string with movie id
		  var url =
			this.api_key_prefix + this.hidden_movies[i].tmdb_id + "?" + this.api_key;
		  // get movie object from TMDb by tmdb_id
		  $.getJSON(url).done(function(response) {
			// put the received movie object into array
			self.hidden_movies_display.push(response);
		  });
		}
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
        var backgroundImage = urlImage + "original" + backgroundPath;
        $(".modal").css("background-image", "url(" + backgroundImage + ")");
      } else {
        $(".modal").css("background", "#636e72");
      }

      $("#movie_info").modal("show");
    },


    // ------------------------------------------------------------------------------------------
    hideMovie(tmdb_id) {
      axios.post("/hide", {
        tmdb_id: tmdb_id
      });
    },


    // ------------------------------------------------------------------------------------------
    laterMovie(tmdb_id) {
      axios.post("/watchlater", {
        tmdb_id: tmdb_id
      });
    },

    // ------------------------------------------------------------------------------------------
    rateMovie(tmdb_id, rating) {
      // mirror the rating value (1 => 5, 2 => 4, ...)
      rating = 6 - rating;

      if (this.ratings[tmdb_id] !== rating) {
        axios
          .post("/rate", {
            tmdb_id: tmdb_id,
            user_rating: rating
          })
          // reflect changes in the local array
          .then(response => {
            this.ratings[tmdb_id] = rating;
          });
      }
    },

  }
};
</script>
