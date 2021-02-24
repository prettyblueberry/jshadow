<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script> --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('js/moment.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="    https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style type="text/css">
        @font-face { font-family: 'Cinnabar Brush';src:url({{asset('fonts/cinnabar.ttf')}}) format('truetype');}
    </style>
</head>
<body>
<div id="app">
    <div class="row" style="background-color: #df1a41;">
        <div class="col-md-12 text-right social-icons">
            <a href="https://www.facebook.com/messages/t/JobShadowBookings" target="_blank"><i class="fab fa-facebook-f"></i></i></a>
            <a href="https://www.instagram.com/jobshadowbookings/" target=""><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    <nav class="navbar navbar-expand-xl navbar-light navbar-laravel">
        {{-- <div class="container"> --}}
        <a class="navbar-brand" href="https://jobshadow.co.za/" style="width: 25%;">
            <img class="img-fluid" style="max-width: 100%;" src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse text-right" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/about/">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/real-stories/">Real Stories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/how-to/">How To</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/send-to-a-friend/">Send to a Friend</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/career-guidance-tool/">Career Guidance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link single" href="https://jobshadow.co.za/contact-us/">Contact us</a>
                </li>
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link single" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link single" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @else
                    @admin
                    {{-- <li class="nav-item">
                        <a class="nav-link single" href="{{ action('ApplicationController@index') }}">Applications</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link single" href="{{ action('JobController@index') }}">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link single" href="{{ action('VoucherController@index') }}">Vouchers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link single" href="{{ action('ApplicationController@index') }}">Applications</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link single" href="{{ action('ReportController@index') }}">Reports</a>
                    </li> --}}
                    @endadmin
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ action('ProfileController@edit', Auth::user()->id) }}">My Account</a>
                            <a class="dropdown-item" href="{{ action('ProfileController@applicationsByUser', Auth::user()->id) }}">My Bookings</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
        {{-- </div> --}}
    </nav>

    <main class="py-4 {{ \Route::current()->getName() }}">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 text-center">
        <div class="container">
            <span class="" style="color:white;">© JobShadow {{ date('Y') }}</span>
        </div>
    </footer>
</div>

</body>
</html>
