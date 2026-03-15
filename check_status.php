<?php
include("connection.php");
$res = mysqli_query($con, "SELECT DISTINCT status FROM garbageinfo");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['status'] . "\n";
}
?>
