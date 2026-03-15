<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="signup-user.php" method="POST" autocomplete="">
                    <h2 class="text-center">Signup Form</h2>
                    
                    <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="Full Name" required value="<?php echo $name ?>" pattern="[A-Za-z\s]+" title="Only alphabets and spaces are allowed">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" id="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>" pattern=".+@((gmail\.com|yahoo\.com|yahoo\.in|yahoo\.co\.in|ymail\.com|outlook\.com|hotmail\.com|live\.com|msn\.com|icloud\.com|me\.com|mac\.com|zoho\.com|proton\.me|protonmail\.com|aol\.com|gmx\.com|gmx\.net|tcs\.com|infosys\.com|google\.com)|.+\.(edu|edu\.in|ac\.in|gov|gov\.in|nic\.in|org|org\.in))$" title="Please enter a valid email address">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Password" required onkeyup="validatePassword()">
                        <small id="passHelp" class="form-text text-danger" style="display:none;">Must be 8+ chars, incl. UPPER, lower, number, special symbol.</small>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" id="submitBtn" name="signup" value="Signup">
                    </div>
                    <div class="link login-link text-center">Already a member? <a href="login-user.php">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function validatePassword() {
            var password = document.getElementById('password').value;
            var submitBtn = document.getElementById('submitBtn');
            var errorMsg = document.getElementById('passHelp');
            
            // Regex: Min 8, 1 Upper, 1 Lower, 1 Digit, 1 Special
            var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

            if (strongRegex.test(password)) {
                errorMsg.style.display = 'none';
                submitBtn.disabled = false; 
            } else {
                errorMsg.style.display = 'block';
                submitBtn.disabled = true;
            }
        }
    </script>
</body>
</html>