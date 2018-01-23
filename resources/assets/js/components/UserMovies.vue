<template>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          My Movies
        </div>
        <!-- rated movies table -->
        <div class="panel-body">
          <table class="table table-bordered table-striped table-responsive"
                 v-if="TMDBmovies.length > 0">
            <tbody>
              <tr>
                <th>
                  Movie Title
                </th>
                <th>
                  My Rating
                </th>
                <th>
                  Overal Rating
                </th>
                <th>
                  Hidden
                </th>
                <th>
                  Watch Later
                </th>
              </tr>
              <!-- loop to display the movie data in the table -->
              <tr v-for="(movie, index) in TMDBmovies">
                <td>
                  <!-- title is taken from TMDBmovies array -->
                  <a @click="showMovieInfo(index)">{{ movie.title }}</a>
                </td>
                <!-- my rating is taken from MyDBmovies array -->
                <td>{{ MyDBmovies[index].ratio }}</td>
                <!-- average rating is taken from TMDBmovies array -->
                <td>{{ movie.vote_average }}</td>
                <!-- show whether movie is hidden -->
                <!-- information is taken from MyDBmovies array -->
                <td v-if="MyDBmovies[index].hidden == 1">YES</td>
                <td v-else>NO</td>
                <!-- show whether movie is in the watch later list -->
                <!-- information is taken from MyDBmovies array -->
                <td v-if="MyDBmovies[index].watchlater == 1">YES</td>
                <td v-else>NO</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- modal window, selected movie info -->
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
                <li><span class="li_header">Countries:</span>
                  <!-- loop to display all involved countries -->
                  <span v-for="(country, index) in movie.production_countries">
                    {{country.name}}
                    <!-- display a comma if there is a following value -->
                    <span v-if="movie.production_countries[index + 1] != null">,</span>
                  </span>
                </li>
                <!-- genres -->
                <li><span class="li_header">Genres: </span>
                  <!-- loop to display all genres -->
                  <span v-for="(genre, index) in movie.genres">
                    {{genre.name}}
                    <!-- display a comma if there is a following value -->
                    <span v-if="movie.genres[index + 1] != null">,</span>
                  </span>
                </li>
                <!-- runtime -->
                <li><span class="li_header">Runtime: </span> {{movie.runtime}} min</li>
                <!-- overview -->
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
      image_prefix_url: 'http://image.tmdb.org/t/p/w185'
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
    // show a modal window with information about the selected movie
    showMovieInfo(movieIndex) {
      // put the selected movie into object (temporary container)
      this.movie = this.TMDBmovies[movieIndex]
      // show modal window
      $('#movie_info').modal('show')
    }
  }
}
</script>
