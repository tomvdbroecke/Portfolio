<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <title>Tom van den Broecke - {{ $Project->name }}</title>

        <!-- OGP Tags -->
        <meta property="og:title" content="Tom van den Broecke - {{ $Project->name }}">
        <meta property="og:description" content="A section of the website for clients to get updates on their projects.">
        <meta property="og:image" content="{{ URL::asset('assets/thumbnail.png') }}">
        <meta property="og:url" content="https://www.tomvdbroecke.com/login">
        <meta property="og:type" content="website">

        <!-- Metatags -->
        <meta name="author" content="Tom van den Broecke - {{ $Project->name }}">
        <meta name="description" content="A section of the website for clients to get updates on their projects.">
        <meta name="image" content="{{ URL::asset('assets/thumbnail.png') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">

        <!-- Style Includes -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('css/projectview_style.css') }}">

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
    <body>
        <p>WELL BIG YEET</p>
    </body>
</html>