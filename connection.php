<?php
// Session configuration for portability
$session_path = __DIR__ . '/sessions';
if (!file_exists($session_path)) {
    mkdir($session_path, 0777, true);
}
session_save_path($session_path);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "127.0.0.1";
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

$user = "root";
$password = "root";
$database = "wms";

mysqli_report(MYSQLI_REPORT_OFF);
$con = @mysqli_connect($host, $user, $password, $database);
if (!$con) {
    $con = mysqli_connect($host, $user, "", $database);
}
$conn = $con; // Alias for backward compatibility

if (!$con) {
    die("Database connection failed. Please ensure MySQL is running and the 'wms' database exists. Error: " . mysqli_connect_error());
}
// End of file