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

                <!-- ROW 1  -->
                <div class="row">
                    <h2>Popular Movies:</h2>
                    
                    <div class="slider slider-nav">

                        <a v-if="index < 30"
                           v-for="(movie,index) in popular_movies" href="#" class="smSlickItem">

                            <img
                                    v-bind:src="image_prefix_url + movie.poster_path" class="slickImage"
                                    @click="showMovie(movie.id)">

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
                </div><!-- ROW 1 -->

                <!-- ROW 2 -->
                <div class="row">
                    <h2 v-if="final_recommendations.length == 0">High Rated Movies:</h2>
                    <h2 v-else>Recommended Movies:</h2>

                    <div class="slider slider-nav">

                        <a v-if="index < 30"
                           v-for="(movie,index) in second_line_movies" href="#" class="smSlickItem">

                            <img
                                    v-bind:src="image_prefix_url + movie.poster_path" class="slickImage"
                                    @click="showMovie(movie.id)">

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
                // contains all highest rated movies received from TMDb
                high_rated_movies: [],
                // recommended movies (without )
                recommended_array: [],
                final_recommendations: [],
                second_line_movies: [],
                // tmdb api key value
                api_key: "api_key=a3abe9699d800e588cb2a57107b4179c",
                // url prefix for getting posters
                image_prefix_url: "http://image.tmdb.org/t/p/w500",
                
            };
        },

        // functions triggered when Vue object is mounted
        mounted() {
            // get most popular and highest rated movies to display them in the first line
            this.getPopularMovies(),
            this.getHighRatedMovies(),
            // check DB for previously saved recommendations
            // depending on the checking result, run a function to fill the second line of movies
            this.checkRecommendations() 
        },

        methods: {
          
            checkRecommendations() {

                // check DB for previously saved recommendations
                axios.get("/check_recommendations")
                        .then(response => {
                                        // if there are no any previously saved recommendations
                                        if(response.data.recommended == 'nothing') {

                                            // if "I" have rated more than 50 movies - try to get new recommendations!
                                            if(response.data.rated > 50) {
                                                this.getRecommendations();
                                            }
                                            // or just get the list of highest rated movies
                                            else {
                                                this.getHighRatedMovies();
                                                this.useHighRatedMovies();
                                            }
                                        }
                                        // if "I" have previously saved recommendations (movies)
                                        else {
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
                axios.get("/get_recommendations")
                     .then(response => {
                                        // if recommendations are present
                                        if(response.data.recommended != 'nothing') {

                                            // put them into a local array (create reference)
                                            this.recommended_array = response.data.recommended;

                                            // then store them in DB
                                            axios.post("/save_recommendations", this.recommended_array);

                                            this.getRecommendedInfo();
                                        }
                                        else {
                                            this.useHighRatedMovies();
                                        }
                });
            },

            getRecommendedInfo() {

                this.recommended_array.forEach(movie => {

                    // url query to find movie by tmdb_id
                    var url = "https://api.themoviedb.org/3/movie/" + movie.movie_id + "?" + this.api_key;
                    // reference to Vue object
                    var self = this;

                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                                       // put the movie object to local object "movie"
                                       self.final_recommendations.push(json);
                             })
                        .then(this.second_line_movies = this.final_recommendations);
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
                $.getJSON(url).done(function (received_movies) {
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
                $.getJSON(url).done(function (response) {
                    // put the received movies into array
                    self.high_rated_movies = response.results;
                });   
            },

            // show selected movie info in modal window
            showMovie(movieId) {
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
        },
    };
</script>
