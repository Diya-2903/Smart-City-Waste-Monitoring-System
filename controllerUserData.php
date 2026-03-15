<?php
require "connection.php";
$email = "";
$name = "";
$errors = array();

//if user signup button
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered is already exist!";
    }

    // Server-side validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = "Name must contain only alphabets and spaces!";
    }
    // Strong password validation
    if (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[!@#\$%\^&\*]/", $password)
    ) {
        $errors['password'] = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special symbol.";
    }
    // Generic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "It looks like you are not entering a valid email!";
    } else {
        // Allowed exact domains
        $allowed_domains = [
            'gmail.com',
            'yahoo.com',
            'yahoo.in',
            'yahoo.co.in',
            'ymail.com',
            'outlook.com',
            'hotmail.com',
            'live.com',
            'msn.com',
            'icloud.com',
            'me.com',
            'mac.com',
            'zoho.com',
            'proton.me',
            'protonmail.com',
            'aol.com',
            'gmx.com',
            'gmx.net',
            'tcs.com',
            'infosys.com',
            'google.com'
        ];

        // Allowed suffixes (for edu, gov, org)
        $allowed_suffixes = [
            '.edu',
            '.edu.in',
            '.ac.in',
            '.gov',
            '.gov.in',
            '.nic.in',
            '.org',
            '.org.in'
        ];

        $domain = substr(strrchr($email, "@"), 1);
        $is_allowed = false;

        // Check exact domains
        if (in_array($domain, $allowed_domains)) {
            $is_allowed = true;
        }
        // Check suffixes
        else {
            foreach ($allowed_suffixes as $suffix) {
                if (substr($domain, -strlen($suffix)) === $suffix) {
                    $is_allowed = true;
                    break;
                }
            }
        }

        if (!$is_allowed) {
            $errors['email'] = "Please enter a valid email address.";
        } else {
            // DNS Validation check
            if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
                $errors['email'] = "The email domain ($domain) does not seem to exist or has no mail server.";
            }
        }
    }
    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status)
                        values('$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if ($data_check) {
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: bistajanak303@gmail.com";
            if (true) { // mail($email, $subject, $message, $sender)
                $info = "We've sent a verification code to your email - $email. Code: $code";
                file_put_contents('otp_log.txt', "Email: $email - Code: $code\n", FILE_APPEND); // Log OTP for debugging
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }

}
//if user click verification code submit button
if (isset($_POST['check'])) {
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if ($update_res) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: index.html');
            exit();
        } else {
            $errors['otp-error'] = "Failed while updating code!";
        }
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click login button
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if ($status == 'verified') {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: phpGmailSMTP/trash.php');
                exit();
            } else {
                $code = $fetch['code']; // Get the code from the database
                $info = "It's look like you haven't still verify your email - $email. Code: $code";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
    }
}

//if user click continue button in forgot password form
if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if (mysqli_num_rows($run_sql) > 0) {
        $code = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        $run_query = mysqli_query($con, $insert_code);
        if ($run_query) {
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: janak.bista@sagarmatha.edu.np";
            if (true) { // mail($email, $subject, $message, $sender)
                $info = "We've sent a passwrod reset otp to your email - $email. Code: $code";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

//if user click check reset otp button
if (isset($_POST['check-reset-otp'])) {
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: new-password.php');
        exit();
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long!";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $errors['password'] = "Password must include at least one number!";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $errors['password'] = "Password must include at least one lowercase letter!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $errors['password'] = "Password must include at least one uppercase letter!";
    } elseif (!preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password)) {
        $errors['password'] = "Password must include at least one special character!";
    } else {
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if ($run_query) {
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

//if login now button click
if (isset($_POST['login-now'])) {
    header('Location: login-user.php');
}