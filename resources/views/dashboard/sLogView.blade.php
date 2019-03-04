<?php

    $stream = fopen(storage_path('logs/laravel.log'), 'r');
    if ($stream) {
        while (($line = fgets($stream)) !== false) {
            echo $line . "<br>";
        }

        fclose($stream);
    } else {
        echo "Could not open log file.";
    } 

?>