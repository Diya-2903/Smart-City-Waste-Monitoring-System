<?php
mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli("127.0.0.1", "root", "root", "wms");

if ($db->connect_error) {
    $db = new mysqli("127.0.0.1", "root", "", "wms");
    if ($db->connect_error) {
        die("Please check your database connection: " . $db->connect_error);
    }
}

// For compatibility with scripts using $con or $conn
$con = $db;
$conn = $db;