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
          $('.slider-nav2').slick({
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
