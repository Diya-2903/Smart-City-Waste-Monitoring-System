<?php
include("adminsignup/connection.php");

$name = "Mahi Patel";
$email = "patelmahi1422006@gmail.com";
$password = "Mahi@2006";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$status = "verified";
$code = 0;

// Check if exists
$check = mysqli_query($con, "SELECT * FROM adminlogin_tbl WHERE email = '$email'");
if (mysqli_num_rows($check) > 0) {
    $sql = "UPDATE adminlogin_tbl SET password = '$hashed_password', status = '$status' WHERE email = '$email'";
    $action = "Updated";
} else {
    $sql = "INSERT INTO adminlogin_tbl (name, email, password, code, status) VALUES ('$name', '$email', '$hashed_password', $code, '$status')";
    $action = "Inserted";
}

if (mysqli_query($con, $sql)) {
    echo "$action user successfully in adminlogin_tbl.\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "Hash: $hashed_password\n";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
