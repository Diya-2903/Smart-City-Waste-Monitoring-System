<?php
include("adminlogin/connection.php");
$result = mysqli_query($db, "SELECT * FROM adminlogin");
if ($result) {
    echo "Count: " . mysqli_num_rows($result) . "\n";
    while ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
} else {
    echo "Error: " . mysqli_error($db);
}
?>
