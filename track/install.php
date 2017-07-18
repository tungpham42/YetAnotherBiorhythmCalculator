<?php
    include 'dbconfig.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>userTrack installation</title>
    <link rel= "shortcut icon" media="all" type="image/x-icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/setup.css">
</head>
<body>

    <?php

        
        // Step 1: Create the database
        if(isset($db_error)) {

            // The form was submitted
            if(isset($_GET['db_name'])) {

                /*** Update the connection info from the form ***/

                // Directly edit the dbconfig.php file
                $filename = 'dbconfig.php';
                $contents = file_get_contents($filename);

                // Username
                $pattern = '/username = "\S*";/';
                $newpat = 'username = "'. $_GET['db_user'] .'";';
                $contents = preg_replace($pattern, $newpat, $contents);
    
                // Password
                $pattern = '/password = "\S*";/';
                $newpat = 'password = "'. $_GET['db_pass'] .'";';
                $contents = preg_replace($pattern, $newpat, $contents);

                // Database name
                $pattern = '/db_name = "\S*";/';
                $newpat = 'db_name = "'. $_GET['db_name'] .'";';
                $contents = preg_replace($pattern, $newpat, $contents);

                // Save the file
                file_put_contents($filename, $contents);
    
                echo "<script>location.href = 'install.php'</script>";
                die();
            }

            include 'setup/view/db_setup.php';
        } else {

            // Step 2: Display default username & password
            include 'setup/view/login_info.php';
        }
    ?>
</body>
</html>
