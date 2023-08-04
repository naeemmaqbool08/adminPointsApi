<?php
require_once "config.php";
if (!version_compare(PHP_VERSION, '5.5.0', '>=')) {
    exit("Required PHP_VERSION >= 5.5.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}
date_default_timezone_set('UTC');
session_start();
require_once('assets/libraries/DB/vendor/joshcam/mysqli-database-class/MySQL-Maria.php');
require_once('includes/cache.php');
require_once('includes/tabels.php');
require_once('includes/functions_general.php');
require_once('includes/functions_one.php');
require_once('includes/app_start.php');
