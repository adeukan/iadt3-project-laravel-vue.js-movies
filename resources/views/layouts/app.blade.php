<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('slickfolder/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('slickfolder/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <title>MRS</title>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}"> Movie Recommendation Site </a>

                    
                    
                    <form class="navbar-form navbar-left col-md-12 col-xs-4" role="search">
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Search">
                        </div>
                        <button type="button" class="btn btn-default">Go</button>
                    </form>

                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right navStyle">

                        <!-- Authentication Links -->
                        @guest
                            {{-- <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li> --}}
                        @else
                            <!-- links to all pages (Vue components) -->
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/profile') }}">Profile</a></li>
                            <li><a href="{{ url('/lists') }}">Lists</a></li>
                            

                            <li class="dropdown">

                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>  

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.0.0.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('slickfolder/slick/slick.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){
          $('.slider-nav').slick({
                dots:false,
                slidesToShow: 5,
                slidesToScroll: 4,
                infinite:true,
                variableWidth: true,
                responsive: [
                    {
                      breakpoint: 1300,
                      settings: {
                        slidesToShow: 3,
                        slidesToScroll: 2,
                      }
                    },
                    {
                      breakpoint: 480,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                      }
                    }
                ]
          });
        });

    </script>
</body>
</html>
