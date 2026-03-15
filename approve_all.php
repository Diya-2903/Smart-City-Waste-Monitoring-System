<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = mysqli_connect('127.0.0.1', 'root', 'root', 'wms');
$query = "UPDATE garbageinfo SET status = 'Approved'";
mysqli_query($con, $query);
echo "Updated " . mysqli_affected_rows($con) . " rows.";
?>
