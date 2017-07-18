<?php

    /*** Directly edit the tracker.js file ***/
    $filename = '../tracker.js';
    $contents = file_get_contents($filename);
    
    // Path to this file without the file
    $path = preg_replace('#\/[^/]*$#', '', $_SERVER['REQUEST_URI']);
    
    // Set server path to current link
    $serverPath = dirname("//$_SERVER[HTTP_HOST]$path");
        
    $pattern = '/serverPath: \S+,/';
    $newpat = 'serverPath: "'.$serverPath .'",';
    $contents = preg_replace($pattern, $newpat, $contents);

    file_put_contents($filename, $contents);
    
    header("location: ../index.html");
?>
