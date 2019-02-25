<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134664416-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-134664416-1');
        </script>

        <title>Tom van den Broecke - Client Dashboard</title>

        <!-- OGP Tags -->
        <meta property="og:title" content="Tom van den Broecke - Client Dasboard">
        <meta property="og:description" content="A section of the website for clients to get updates on their projects.">
        <meta property="og:image" content="{{ URL::asset('assets/thumbnail.png') }}">
        <meta property="og:url" content="https://www.tomvdbroecke.com/login">
        <meta property="og:type" content="website">

        <!-- Metatags -->
        <meta name="author" content="Tom van den Broecke - Client Dasboard">
        <meta name="description" content="A section of the website for clients to get updates on their projects.">
        <meta name="image" content="{{ URL::asset('assets/thumbnail.png') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">

        <!-- Style Includes -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('css/dashboard_style.css') }}">

        <!-- Include Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('assets/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('assets/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="256256" href="{{ URL::asset('assets/favicons/favicon-256x256.png') }}">
        <link rel="icon" type="image/png" sizes="264x168" href="{{ URL::asset('assets/favicons/speeddial-264x168.png') }}">
        <link rel="manifest" href="{{ URL::asset('assets/favicons/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ URL::asset('assets/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
        <link rel="shortcut icon" href="{{ URL::asset('assets/favicons/favicon.ico') }}">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="msapplication-config" content="{{ URL::asset('assets/favicons/browserconfig.xml') }}">
        <meta name="theme-color" content="#060d21">

    </head>
    <body class="dashboard_body">
        <div class="dashboard_background_image"></div>
        <div class="dashboard_background">
            <div class="middle_man">
                <div class="centered dashboard_holder container" data-aos="fade-in">
                    <div class="dashboard_head">
                        <nav class="navbar navbar">
                            <div class="navbar-header">
                                <a class="navbar-brand">Dashboard</a>
                            </div>
                            <ul class="nav navbar-nav">
                                <a>Logged in as {{ $User->name }} <i class="fas fa-user-circle"></i></a>
                            </ul>
                        </nav>
                    </div>
                    <div class="dashboard_content">
                        <div class="row">
                            <div id="side_navbar" class="col-4 col-sm-4">
                                <ul class="sidenav">
                                    <li><a href="/dashboard/account"{{ $activePage === "account" ? ' class=active' : '' }}>Account</a></li>
                                    <li><a href="/dashboard/projects"{{ $activePage === "projects" ? ' class=active' : '' }}>Projects</a></li>
                                    @if($User->IsAdmin())
                                    <li><a href="/dashboard/console"{{ $activePage === "console" ? ' class=active' : '' }}>Console</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div id="content" class="col-8 col-sm-8">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_footer">
                        <p class="db_note">Beta v0.1.23</p>
                        <a class="btn btn-primary btn-logout" href="/logout">Log Out</a>
                    </div>  
                </div>
            </div>
        </div>

        <!-- JavaScript Includes -->
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- Initialize Scroll Animations -->
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>AOS.init();</script>
    </body>
</html>