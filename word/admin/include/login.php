<?php if (!defined('BASE_PATH')) die("No direct access allowed");
    // redirect user is logged
    if ($_POST)
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // log in admin
        if ($username == $config->username && $password == $config->password)
        {
            $_SESSION['logged'] = TRUE;
            redirect('index.php');
        }
    }
    else if (isset($_GET['logout']))
    {
        unset($_SESSION['logged']);
        redirect('index.php');
    }
