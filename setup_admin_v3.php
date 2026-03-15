<?php
require "adminlogin/connection.php";

$name = "Test Admin";
$email = "testadmin@example.com";
$password = "password123";
$encpass = password_hash($password, PASSWORD_BCRYPT);
$code = 0;
$status = "verified";

// Check if exists
$check = "SELECT * FROM adminlogin_tbl WHERE email = '$email'";
$res = mysqli_query($db, $check);

if(mysqli_num_rows($res) > 0){
    echo "Admin already exists. Updating password.\n";
    $sql = "UPDATE adminlogin_tbl SET password='$encpass', status='$status' WHERE email='$email'";
} else {
    echo "Creating new admin.\n";
    $sql = "INSERT INTO adminlogin_tbl (name, email, password, code, status)
            VALUES ('$name', '$email', '$encpass', '$code', '$status')";
}

if(mysqli_query($db, $sql)){
    echo "Admin setup successful!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "Error: " . mysqli_error($db);
}
?>
