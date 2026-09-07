<?php 
session_start();
error_reporting(0);
include("include/config.php");

$errorMsg = "";
$emailValue = "";

if (isset($_POST['submit'])) {
    $puname = trim($_POST['username']);	
    $ppwd = $_POST['password'];
    $emailValue = htmlspecialchars($puname, ENT_QUOTES);

    // Using prepared statements to avoid SQL Injection
    $stmt = $con->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $puname);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->fetch_assoc();
    
    if ($num) {
        $dbPass = $num['password'];

        // Check if password is MD5 (our old hash kabla ya kugundua bcryt is better)
        if (strlen($dbPass) == 32 && md5($ppwd) === $dbPass) {
            // Successful login with old MD5 password -> migrate to bcrypt
            $newHash = password_hash($ppwd, PASSWORD_BCRYPT);
            $updateStmt = $con->prepare("UPDATE users SET password=? WHERE id=?");
            $updateStmt->bind_param("si", $newHash, $num['id']);
            $updateStmt->execute();
            $dbPass = $newHash; // update for login below
        }

        // Verify password with bcrypt
        if (password_verify($ppwd, $dbPass)) {
            // Login success
            session_regenerate_id(true); 
            $_SESSION['login'] = $puname;
            $_SESSION['id'] = $num['id'];

            $pid = $num['id'];
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 1;

            $logStmt = $con->prepare("INSERT INTO userlog(uid, username, userip, status) VALUES (?, ?, ?, ?)");
            $logStmt->bind_param("issi", $pid, $puname, $uip, $status);
            $logStmt->execute();

            header("Location: dashboard.php");
            exit();
        } else {
            // Wrong password
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 0;
            $logStmt = $con->prepare("INSERT INTO userlog(username, userip, status) VALUES (?, ?, ?)");
            $logStmt->bind_param("ssi", $puname, $uip, $status);
            $logStmt->execute();

            $errorMsg = "Invalid username or password. Please try again.";
        }
    } else {
        // User not found
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        $logStmt = $con->prepare("INSERT INTO userlog(username, userip, status) VALUES (?, ?, ?)");
        $logStmt->bind_param("ssi", $puname, $uip, $status);
        $logStmt->execute();

        $errorMsg = "Invalid username or password. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>IFM Hospital | Patient Login</title>
    <link href="http://fonts.googleapis.com/css?family=Poppins:400,600|Lato:400,700" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url('../assets/images/patienthands.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 45px;
            width: 380px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            text-align: center;
            color: #fff;
        }

        .login-container h2 { font-weight: 600; margin-bottom: 8px; }
        .login-container p.subtitle { font-size: 14px; margin-bottom: 25px; opacity: 0.85; }

        .form-group { text-align: left; margin-bottom: 20px; position: relative; }
        label { font-weight: 500; font-size: 14px; margin-bottom: 5px; display: block; color: #f5f5f5; }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding: 12px 40px 12px 15px;
            width: 100%;
        }
        .form-control::placeholder { color: #ddd; }

        /* Show na Hide Password Icon */
        .toggle-password {
            position: absolute;
            top: 38px;
            right: 15px;
            color: #00c6ff;
            cursor: pointer;
            font-size: 17px;
            transition: 0.3s;
        }
        .toggle-password:hover {
            color: #80e0ff;
        }

        .btn-login {
            background: linear-gradient(90deg, #0072ff, #00c6ff);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 12px 0;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover { transform: translateY(-2px); background: linear-gradient(90deg, #0057e7, #0099ff); }

        .error-box {
            display: none;
            background: rgba(255, 0, 0, 0.25);
            color: #ffb3b3;
            border-left: 4px solid #ff4d4d;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .footer-note { font-size: 13px; opacity: 0.7; margin-top: 25px; }
        a { color: #00c6ff; text-decoration: none; transition: color 0.2s; }
        a:hover { color: #80e0ff; }
    </style>
</head>

<body>
<div class="login-container">
    <h2>PATIENT LOGIN</h2>
    <p class="subtitle">Institute of Finance Management — Clinic</p>

    <div class="error-box" id="errorBox" <?php if($errorMsg) echo 'style="display:block;"'; ?>>
        <i class="fa fa-exclamation-circle"></i> <?php echo $errorMsg; ?>
    </div>

    <form method="post">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" class="form-control" name="username" placeholder="Enter your email"
                   value="<?php echo $emailValue; ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" id="passwordField" class="form-control" name="password" placeholder="Enter password" required>
            <i class="fa fa-eye toggle-password" id="togglePassword" aria-label="Show or hide password" role="button" tabindex="0"></i>
            <a href="forgot-password.php" style="font-size:13px; float:right; margin-top:6px;">Forgot Password?</a>
        </div>

        <button type="submit" name="submit" class="btn-login">Login</button>
    </form>

    <div style="margin-top:20px;">
        <p>Don’t have an account? <a href="registration.php">Create one</a></p>
    </div>

    <div class="footer-note">
        &copy; <?php echo date("Y"); ?> IFM Hospital Management System
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        if($('#errorBox').is(':visible')){
            $('#errorBox').hide().slideDown(300);
        }

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('passwordField');
        
        function togglePassVisibility() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        }

        togglePassword.addEventListener('click', togglePassVisibility);
        togglePassword.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                togglePassVisibility();
            }
        });
    });
</script>
</body>
</html>
