<?php header('X-Frame-Options: SAMEORIGIN'); ?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>
        <title>ENV EDITOR</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body style="padding: 10px;">
        <!-- HTML form -->
        <form action="/dashboard/projects/editEnv" method="post">
            @csrf
            <input type="hidden" name="project_id" value="{{ $Project->id }}">
            <textarea name="env-text" class="col-12" style="height: 80vh"><?php
                $lines = file($envFile, FILE_IGNORE_NEW_LINES);
                foreach ($lines as $line) {
                    echo "$line\n";
                }
            ?></textarea>
            <div class="menu">
                <input type="submit" name="env-submit" value="Save">
                <input type="submit" name="env-cancel" value="Cancel">
            </div>
        </form>
    </body>
</html>