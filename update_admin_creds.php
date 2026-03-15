<?php
include("adminlogin/connection.php");

$username = "patelmahi1422006@gmail.com";
$password = "Mahi@2006";

// Check if exists
$check = mysqli_query($db, "SELECT * FROM adminlogin WHERE username = '$username'");
if (mysqli_num_rows($check) > 0) {
    $sql = "UPDATE adminlogin SET password = '$password' WHERE username = '$username'";
    $action = "Updated";
} else {
    $sql = "INSERT INTO adminlogin (username, password) VALUES ('$username', '$password')";
    $action = "Inserted";
}

if (mysqli_query($db, $sql)) {
    echo "$action user successfully.\nUsername: $username\nPassword: $password\n";
} else {
    echo "Error: " . mysqli_error($db);
}
?>
