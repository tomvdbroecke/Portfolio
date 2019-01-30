<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <title>Tom van den Broecke</title>

        <!-- Metatags -->
        <meta name="author" content="Tom van den Broecke">
        <meta name="description" content="This is a portfolio website by Tom van den Broecke.">
        <meta name="keywords" content="HTML, CSS, JavaScript, PHP, C#, Programming, Development, Developing, Design, Websites, Full-Stack">

        <!-- Includes -->
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css">
        <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="{{ URL::asset('js/parallax.min.js') }}"></script>

        <!-- Include Favicon -->
        <link rel="icon" href="" type="image/x-icon">
    </head>
    <body>
    @yield('content')

        <!-- Initialize Scroll Animations -->
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>AOS.init();</script>
    </body>
</html>