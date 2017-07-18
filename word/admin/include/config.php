<?php if (!defined('BASE_PATH')) die("No direct access allowed");

$config = array();

$config['defaultGameOptions'] = array(
    'alphabet' => "abcdefghijklmnopqrstuvwxyz",
    'totalWords' => 15,
    'size' => 20,
    'showSolveButton' => 1,
    'showDescription' => 1,
    'every' => 10,
    'deduct' => 2,
    'initialScore' => 1000
);

// login credentials
$config['username'] = "tungdeptrai";

$config['password'] = "DEPTR@Iv0d0i";

// page length
$config['per_page'] = 10;

$config = (object)$config;
