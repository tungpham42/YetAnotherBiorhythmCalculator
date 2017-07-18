<?php if (!defined('BASE_PATH')) die("No direct access allowed"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Wordsearch admin</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap-responsive.css" rel="stylesheet" />
    <script src="js/jquery.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="./">HTML5 WordSearch</a>
        <ul class="nav">
            <?php if (is_logged()): ?>
                <li><a href="?logout">Logout</a></li>
                <li><a href="?edit">Create puzzle</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</div>

<div class="container">
    <div class="row">
