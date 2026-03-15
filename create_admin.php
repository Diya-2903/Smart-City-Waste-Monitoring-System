<?php
include("adminlogin/connection.php");

$username = "test";
$password = "test"; // Storing as plain text to match existing schema
// Note: In a production app, use password_hash()

$sql = "INSERT INTO adminlogin (username, password) VALUES ('$username', '$password')";

if (mysqli_query($db, $sql)) {
    echo "New record created successfully. Username: $username, Password: $password\n";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

// Verify it exists
$check = mysqli_query($db, "SELECT * FROM adminlogin WHERE username='test'");
print_r(mysqli_fetch_assoc($check));
?>
