<?php
    ob_start();
    include 'dbconfig.php';
    ob_end_clean();
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
        function sanitizeStr($string) {
            $string = str_replace("\\", "\\\\", $string);
            return var_export($string, true);
        }

        // Step 1: Create the database
        if(isset($db_error)) {

            // The form was submitted
            if(isset($_POST['db_name'])) {

                /*** Update the connection info from the form ***/

                // Directly edit the dbconfig.php file
                $filename = 'dbconfig.php';
                $contents = file_get_contents($filename);

                // Database host
                $pattern = "/\\\$host = .*;/";
                $newpat = '\$host = '. sanitizeStr($_POST['db_host']) .';';
                $contents = preg_replace($pattern, $newpat, $contents);
                
                // Database name
                $pattern = "/\\\$db_name = .*;/";
                $newpat = '\$db_name = '. sanitizeStr($_POST['db_name']) .';';
                $contents = preg_replace($pattern, $newpat, $contents);
                
                // Username
                $pattern = "/\\\$username = .*;/";
                $newpat = '\$username = '. sanitizeStr($_POST['db_user']) .';';
                $contents = preg_replace($pattern, $newpat, $contents);
    
                // Password
                $pattern = "/\\\$password = .*;/";
                $newpat = '\$password = '. sanitizeStr($_POST['db_pass']) .';';
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
