<?php
    session_start();
    define('BASE_PATH', 1);
    include('include/config.php');
    include('include/utils.php');
    include('include/Storage.php');

    if ($_POST && !is_logged() || isset($_GET['logout'])) {
        include('include/login.php');
    }

    // which page to load
    if (!is_logged()) {
        $template = 'login';

    } else if (isset($_GET['delete']) && isset($_POST['id'])) {
        delete_puzzle($_POST['id']);

    } else if (isset($_GET['edit'])) {
        include('include/edit.php');
        $template = 'edit';

    } else {
        $template = 'puzzle-list';
    }

    include('templates/index.php');
