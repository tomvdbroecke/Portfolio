<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>Tom van den Broecke</title>

        <!-- Metatags -->
        <meta name="author" content="Tom van den Broecke">
        <meta name="description" content="This is a portfolio website by Tom van den Broecke.">
        <meta name="keywords" content="HTML, CSS, JavaScript, PHP, C#, Programming, Development, Developing, Design, Websites, Full-Stack">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Style Includes -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css">
        <link rel="stylesheet" href="{{ URL::asset('css/home_style.css') }}">

        <!-- Include Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('assets/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('assets/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/favicons/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ URL::asset('assets/favicons/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ URL::asset('assets/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
        <link rel="shortcut icon" href="{{ URL::asset('assets/favicons/favicon.ico') }}">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="msapplication-config" content="{{ URL::asset('assets/favicons/browserconfig.xml') }}">
        <meta name="theme-color" content="#060d21">
    </head>
    <body>
@yield('content')

        <!-- JavaScript Includes -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ URL::asset('js/parallax.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/home.js') }}"></script>
        <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
        <script type="text/javascript" src="{{ URL::asset('js/particles.js') }}"></script>

        <!-- Initialize Scroll Animations -->
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>AOS.init();</script>
    </body>
</html>