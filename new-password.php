<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">New Password</h2>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Create new password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="Must contain at least one number, one uppercase, one lowercase, one special character, and at least 8 or more characters">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        var password = document.querySelector('input[name="password"]').value;
        var errorMsg = "";

        if (password.length < 8) {
            errorMsg = "Password must be at least 8 characters long!";
        } else if (!/[0-9]/.test(password)) {
            errorMsg = "Password must include at least one number!";
        } else if (!/[a-z]/.test(password)) {
            errorMsg = "Password must include at least one lowercase letter!";
        } else if (!/[A-Z]/.test(password)) {
            errorMsg = "Password must include at least one uppercase letter!";
        } else if (!/[^a-zA-Z0-9]/.test(password)) {
            errorMsg = "Password must include at least one special character!";
        }

        if (errorMsg) {
            e.preventDefault();
            alert(errorMsg);
        }
    });
    </script>
</body>
</html>