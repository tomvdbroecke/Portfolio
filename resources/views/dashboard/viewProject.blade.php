<?php header('X-Frame-Options: SAMEORIGIN'); ?>
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
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/dashboard/projects"><i class="fas fa-angle-double-left"></i> Back</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="navbar-nav mr-auto" style="margin-top: 3px;">
        <li style="margin-right: 10px; margin-left: 5px;"><a>Username: {{ $Project->accessUser }}</a></li>
        <li style="margin-right: 10px; margin-left: 5px;"><a>Password: {{ $Project->accessPass }}</a></li>
        </ul>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <div class="dropdown">
                        <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
                            Changelogs
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="width: 300px; max-height: 500px; overflow-y: auto; white-space: normal; padding: 10px;">
                            @if($Changelogs != NULL)
                                <?php
                                    $logCount = 0;
                                ?>
                                @foreach($Changelogs as $log)
                                    <?php $logCount += 1; ?>
                                    <div style="font-size: 12px;">
                                    <p style="font-weight: bold;">{{ $log->version . " | " . date("d-m-Y", strtotime($log->created_at)) }}</span></p>
                                    {!! $log->changes !!}
                                    </div>
                                    @if($logCount < sizeof($Changelogs))
                                    <div class="dropdown-divider"></div>
                                    @endif
                                @endforeach
                            @else
                            <div>No Changelogs Available</div>
                            @endif
                        </div>
                    </div>
                    @if($Project->additional_info != NULL)
                    <div class="dropdown">
                        <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
                            Info
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="width: 300px; max-height: 500px; overflow-y: auto; white-space: normal; padding: 10px;">
                            <div style="font-size: 12px;">
                            {!! $Project->additional_info !!}
                            </div>
                        </div>
                    </div>
                    @endif
            </ul>
        </div>
        </nav>

        <div class="embed_holder">
            @if(preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false))
            <iframe style="border: none;" src="{{ 'https://tomvdbroecke.com/Projects/' . $Project->name . '_public_' . $Project->secretKey }}" style="width: 100%; height: calc(100vh - 57px);"></iframe>
            @else
            <iframe style="border: none;" src="{{ 'https://www.tomvdbroecke.com/Projects/' . $Project->name . '_public_' . $Project->secretKey }}" style="width: 100%; height: calc(100vh - 57px);"></iframe>
            @endif
        </div>

        <!-- JavaScript Includes -->
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>