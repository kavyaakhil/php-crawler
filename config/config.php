<?php

// DATABASE DETAILS CONSTANT
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookstore');
define('DB_CHAR', 'utf8mb4');

// APP ROOT
define('APP_ROOT', dirname(dirname(__FILE__)));

// URL ROOT
$root_url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
define("URL_ROOT", $root_url);

// SITE NAME
$hostname = getenv('SERVER_NAME');
$cleanup = explode('.', $hostname);
define("SITE_NAME", $cleanup[0]);

error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED); 
