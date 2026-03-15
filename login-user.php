<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(160deg, #5b6ef5 0%, #3ca8d4 55%, #29c9c9 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Wave SVG at bottom */
        .wave-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Card */
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 44px 48px 36px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 12px 48px rgba(0,0,0,0.18);
            position: relative;
            z-index: 1;
        }

        .login-card h2 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a2e;
            text-align: center;
            margin-bottom: 6px;
        }

        .login-card .subtitle {
            font-size: 13.5px;
            color: #666;
            text-align: center;
            margin-bottom: 28px;
        }

        /* Error alert */
        .alert-error {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #c0392b;
            margin-bottom: 18px;
        }

        /* Input styles */
        .input-group {
            width: 100%;
            margin-bottom: 16px;
        }
        .input-group input {
            width: 100%;
            height: 48px;
            border: 1.5px solid #d0d5e8;
            border-radius: 8px;
            padding: 0 16px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
            background: #fafafa;
        }
        .input-group input::placeholder { color: #aaa; }
        .input-group input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }

        /* Password wrapper with toggle */
        .input-wrap {
            position: relative;
        }
        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
            font-size: 15px;
            background: none;
            border: none;
            padding: 0;
        }
        .toggle-pass:hover { color: #6366f1; }

        /* Forgot password */
        .forgot-link {
            display: block;
            text-align: right;
            font-size: 12.5px;
            color: #6366f1;
            text-decoration: none;
            margin-top: -4px;
            margin-bottom: 20px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #4f46e5; text-decoration: underline; }

        /* Login button */
        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #6366f1 0%, #5b5bd6 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.4px;
            transition: transform 0.18s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 16px rgba(99,102,241,0.38);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99,102,241,0.48);
            opacity: 0.95;
        }
        .btn-login:active { transform: translateY(0); }

        /* Signup link */
        .bottom-link {
            margin-top: 22px;
            font-size: 13px;
            color: #555;
            text-align: center;
        }
        .bottom-link a {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
        }
        .bottom-link a:hover { text-decoration: underline; }

        /* Footer */
        .footer-bar {
            position: relative;
            z-index: 1;
            margin-top: 28px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.85);
            text-align: center;
        }
        .footer-bar a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s;
        }
        .footer-bar a:hover { opacity: 0.8; }
        .footer-bar .sep { margin: 0 8px; opacity: 0.6; }

        /* Responsive */
        @media (max-width: 500px) {
            .login-card { padding: 36px 24px 28px; }
        }
    </style>
</head>
<body>

    <!-- Login Card -->
    <div class="login-card">
        <h2>User Login</h2>
        <p class="subtitle">Login with your email and password.</p>

        <?php if(count($errors) > 0): ?>
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php foreach($errors as $showerror){ echo $showerror; } ?>
        </div>
        <?php endif; ?>

        <form action="login-user.php" method="POST" autocomplete="off">

            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
            </div>

            <div class="input-group">
                <div class="input-wrap">
                    <input type="password" name="password" id="passwordField" placeholder="Password" required>
                    <button type="button" class="toggle-pass" onclick="togglePassword()" title="Show/Hide">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <a href="forgot-password.php" class="forgot-link">Forgot password?</a>

            <button type="submit" name="login" class="btn-login">Login</button>

        </form>

        <div class="bottom-link">
            Not yet a member? <a href="signup-user.php">Signup now</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-bar">
        Smart City Waste Monitoring System | 2025-26
        <span class="sep">|</span>
        <a href="adminsignup/adminlogin.php"><i class="fas fa-lock"></i> Login As Admin</a>
    </div>

    <!-- Wave -->
    <svg class="wave-bottom" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 180" preserveAspectRatio="none">
        <path fill="rgba(255,255,255,0.18)" d="M0,80 C360,160 1080,0 1440,100 L1440,180 L0,180 Z"/>
        <path fill="rgba(255,255,255,0.10)" d="M0,120 C480,40 960,160 1440,80 L1440,180 L0,180 Z"/>
        <path fill="rgba(255,255,255,0.25)" d="M0,150 C300,100 900,170 1440,130 L1440,180 L0,180 Z"/>
    </svg>

<script>
function togglePassword() {
    const field = document.getElementById('passwordField');
    const icon  = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>