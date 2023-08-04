<?php

@ini_set("max_execution_time", 0);
@ini_set("memory_limit", "-1");
@set_time_limit(0);
require_once "assets/libraries/DB/vendor/autoload.php";


$wo           = array();
// Connect to SQL Server
$sqlConnect   = $wo["sqlConnect"] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);
// create new mysql connection
$mysqlMaria   = new Mysql;
// Handling Server Errors
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}

$db        = new MysqliDb($sqlConnect);


$wo["site_url"]      = $site_url;
$cache             = new Cache();
if (!is_dir("cache")) {
    $cache->Wo_OpenCacheDir();
}
$wo["purchase_code"] = "";
if (!empty($purchase_code)) {
    $wo["purchase_code"] = $purchase_code;
}

$wo["loggedin"]           = false;