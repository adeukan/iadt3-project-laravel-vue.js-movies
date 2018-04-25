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
							<button type="button"
											class="modal-close"
											data-dismiss="modal">
							</button>
						</div>
						<div class="modal-body">
							<img class="modalImg"
									 v-bind:src="image_prefix_url + movie.poster_path">
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
							<button type="button"
											class="btn btn-default"
											data-dismiss="modal">Close</button>
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
					<a v-if="index < 30"
						 v-for="(movie,index) in popular_movies"
						 href="#"
						 class="smSlickItem">

						<img
								v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id)">

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
				<button @click="laterMovie(movie.id)"><span class="glyphicon glyphicon-floppy-disk"
								aria-hidden="true"></span></button>
				<button @click="hideMovie(movie.id)"><span class="glyphicon glyphicon-eye-close"
								aria-hidden="true"></span></button>
			</div>
		</div>
		</a>
	</div>
</div>
<div class="row">
	<h2 v-if="final_recommendations.length == 0"
			class="noSpace">High Rated Movies:</h2>
	<h2 v-else
			class="noSpace">Recommended Movies:</h2>
	<div class="slider slider-nav">
		<a v-if="index < 30"
			 v-for="(movie,index) in second_line_movies"
			 href="#"
			 class="smSlickItem">

						<img
								v-bind:src="image_prefix_url + movie.poster_path" class="slickImage" @click="showMovie(movie.id)">

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
	<button @click="hideMovie(movie.id)"><span class="glyphicon glyphicon-eye-close"
					aria-hidden="true"></span></button>
	<button @click="laterMovie(movie.id)"><span class="glyphicon glyphicon-floppy-disk"
					aria-hidden="true"></span></button>
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
			movie: {},
			// array with user ratings
			ratings: {},
			// user movies
			my_movies: [],
			// contains all popular movies received from TMDb
			popular_movies: [],
			// contains all highest rated movies received from TMDb
			high_rated_movies: [],
			// recommended movies without full info
			recommended_array: [],
			// recommended movies with full info
			final_recommendations: [],
			// movies to display in the second line
			second_line_movies: [],
			// tmdb api key value
			api_key: 'api_key=a3abe9699d800e588cb2a57107b4179c',
			// url prefix for getting posters
			image_prefix_url: 'http://image.tmdb.org/t/p/w185'
		}
	},

	// functions triggered when Vue object is mounted
	mounted() {

		// get the list of "my" movies and then start next method
		this.getMyMovies()
	},

	methods: {

		// get the list of "my" movies and then start next method 
		getMyMovies() {

			axios.get('/my_movies')
				.then(response => {

					// if there is any response      
					if (response !== undefined) {
						// loop through the response data
						response.data.my_movies.forEach(movie => {
							// and put ID of each my movie to the array
							this.my_movies.push(movie.tmdb_id)
						})
					}

					// get the list of most popular movies, remove "my" movies from the list, display movies in the first line
					this.getPopularMovies()

					// check previously saved recommendations in DB, and call one of the following methods
					this.checkRecommendations()
				}
			)
		},

		checkRecommendations() {

			// check DB for previously saved recommendations
			axios.get('/check_recommendations')
				.then(response => {
					// if there are no any previously saved recommendations
					if (response.data.recommended === 'nothing') {

						// if "I" have rated more than 50 movies - try to get new recommendations!
						if (response.data.rated > 50) {
							this.getRecommendations()
						}

						// or just get the list of highest rated movies
						else {
							this.getHighRatedMovies()
						}
					}
					// if "I" have previously saved recommendations (movies)
					else {
						// put them into a local array (create reference)
						this.recommended_array = response.data.recommended
						// and get full info for each of them
						this.getRecommendedInfo()
					}
				})
		},

		// getting recommendations
		getRecommendations() {

			// first, get the recommendations (list of movies)
			axios.get('/get_recommendations')
				.then(response => {

					// if recommendations are present
					if (response.data.recommended !== 'nothing') {

						// put them into a local array (create reference)
						this.recommended_array = response.data.recommended

						// then store them in DB
						axios.post('/save_recommendations', this.recommended_array)

						// and get full info for each of them
						this.getRecommendedInfo()
					}
					// if recommendations can't be generated, use highest rated movies instead
					else {
						this.getHighRatedMovies()
					}
				})
		},

		// get full info for each of recommended movies
		getRecommendedInfo() {

			this.recommended_array.forEach(movie => {

				// url query to find movie by tmdb_id
				var url = 'https://api.themoviedb.org/3/movie/' + movie.tmdb_id + '?' + this.api_key
				// reference to Vue object
				var self = this

				// make request for each next movie
				fetch(url)
					.then(response => response.json())
					.then(json => {
						// put the movie object to local object "movie"
						self.final_recommendations.push(json)
					})
					.then(this.second_line_movies = this.final_recommendations)
			})
		},

		getPopularMovies() {

			// url query for all popular movies
			var url = 'https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&api_key=a3abe9699d800e588cb2a57107b4179c'
			// reference to Vue object
			var self = this

			// get all popular movies from TMDb
			$.getJSON(url).done(function(response) {

				// get rid of the movies present in any of "my" lists
				response.results.forEach(movie => {
					// if a movie is not in "my" lists, then push it to the "popular" list
					if (self.my_movies.indexOf(movie.id) === -1) {

						self.popular_movies.push(movie)
					}
				})
			})
		},

		getHighRatedMovies() {

			// url query for all highest rated movies
			var url = 'https://api.themoviedb.org/3/discover/movie?sort_by=vote_average.desc&api_key=a3abe9699d800e588cb2a57107b4179c'
			// reference to Vue object
			var self = this

			// get all highest rated movies from TMDb
			$.getJSON(url).done(function(response) {

				// get rid of the movies present in any of "my" lists
				response.results.forEach(movie => {

					// if a movie is not in "my" lists, then push it to the "highest_rated" list
					if (self.my_movies.indexOf(movie.id) === -1) {

						self.high_rated_movies.push(movie)
					}
				})

				// use these movies to display them in the second line
				self.second_line_movies = self.high_rated_movies

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
					// put the movie object to local object "movie"
					self.movie = json
				})

			$('#movie_info').modal('show')
		},

		hideMovie(tmdb_id) {
			// HIDE BUTTON HANDLER
			axios.post('/hide', {
				tmdb_id: tmdb_id
			})

		},
		laterMovie(tmdb_id) {
			// WATCHLATER BUTTON HANDLER

			axios.post('/watchlater', {
				tmdb_id: tmdb_id
			})
		},

		// ADD OR CHANGE RATING ---------------------------------------------------------
		rateMovie(tmdb_id, rating) {

			// mirror the rating value (1 => 5, 2 => 4, ...)
			rating = 6 - rating

			if (this.ratings[tmdb_id] !== rating) {

				axios.post('/rate', {
					tmdb_id: tmdb_id,
					user_rating: rating
				})
					// reflect changes in the local array
					.then(response => {
						this.ratings[tmdb_id] = rating
					})
			}
		} // rateMovie()
	}
};

</script>
