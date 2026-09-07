<?php
session_start();
include("include/config.php");

$errorMessage = '';
$uname = '';

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CAPTCHA
if (empty($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

// Remember Me
if (isset($_COOKIE['remember_admin'])) {
    $uname = $_COOKIE['remember_admin'];
}

// Refresh CAPTCHA
if (isset($_GET['refresh_captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
    echo $_SESSION['captcha'];
    exit;
}

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errorMessage = "Invalid request token.";
    } elseif ($_POST['captcha'] != $_SESSION['captcha']) {
        $errorMessage = "Invalid CAPTCHA.";
        $_SESSION['captcha'] = rand(1000, 9999);
    } else {
        $uname = trim($_POST['username']);
        $upassword = $_POST['password'];

        $stmt = $con->prepare("SELECT id, password FROM admin WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $dbPass = $user['password'];

            // Legacy MD5 or non-bcrypt migration
            if ((strlen($dbPass) == 32 && md5($upassword) === $dbPass) || !str_starts_with($dbPass, '$2y$')) {
                $newHash = password_hash($upassword, PASSWORD_BCRYPT);
                $updateStmt = $con->prepare("UPDATE admin SET password=? WHERE id=?");
                $updateStmt->bind_param("si", $newHash, $user['id']);
                $updateStmt->execute();
                $dbPass = $newHash;
            }

            if (password_verify($upassword, $dbPass)) {
                session_regenerate_id(true);
                $_SESSION['login'] = $uname;
                $_SESSION['id'] = $user['id'];

                if (!empty($_POST['remember'])) {
                    setcookie("remember_admin", $uname, time() + (86400 * 30), "/");
                } else {
                    setcookie("remember_admin", "", time() - 3600, "/");
                }

                header("location:dashboard.php");
                exit;
            } else {
                $errorMessage = "Invalid username or password";
                $_SESSION['captcha'] = rand(1000, 9999);
            }
        } else {
            $errorMessage = "Invalid username or password";
            $_SESSION['captcha'] = rand(1000, 9999);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../../vendor/fontawesome/css/font-awesome.min.css">
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: url('../../assets/images/admin.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.login-box {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 45px 40px;
    width: 420px;
    max-width: 90%;
    box-shadow: 0 15px 40px rgba(0,0,0,0.7);
    color: #fff;
    animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {from {opacity: 0; transform: translateY(20px);} to {opacity: 1; transform: translateY(0);}}
.login-box h2 {text-align: center;margin-bottom: 25px;color: #00bfff;font-weight: 700;letter-spacing: 1px;}
.form-group {margin-bottom: 20px; text-align: center; position: relative;}
.label-text {display:block;margin-bottom:8px;font-weight:500;color:#00bfff;font-size:15px;text-align:left;max-width:300px;margin-left:auto;margin-right:auto;}
.input-group {position:relative;width:100%;max-width:300px;margin:0 auto;}
.input-group i {position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#00bfff;font-size:16px;}
.form-control,.btn-primary,.error-text {width:100%;max-width:300px;padding:14px 45px 14px 45px;border-radius:12px;border:1px solid rgba(255,255,255,0.4);background-color:rgba(255,255,255,0.12);color:#fff;font-size:15px;transition:all 0.3s ease;box-shadow:inset 0 2px 4px rgba(0,0,0,0.3);margin:0 auto;display:block;}
.form-control::placeholder {color:rgba(255,255,255,0.7);}
.form-control:focus {border-color:#00bfff;box-shadow:0 0 12px rgba(0,191,255,0.5), inset 0 2px 4px rgba(0,0,0,0.3);background-color:rgba(255,255,255,0.18);outline:none;color:#fff;}
.btn-primary {font-weight:600;background:linear-gradient(135deg,#00bfff,#0080c0);border:none;transition:all 0.4s ease;margin-top:10px;font-size:16px;}
.btn-primary:hover {background:linear-gradient(135deg,#0080c0,#005f99);transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,0.5);}
.error-text {color:#ff4d4d;font-weight:500;text-align:center;margin-bottom:15px;padding:0;background:none;border:none;box-shadow:none;max-width:300px;margin-left:auto;margin-right:auto;animation:shake 0.3s;}
@keyframes shake {0% { transform: translateX(0); } 25% { transform: translateX(-5px); } 50% { transform: translateX(5px); } 75% { transform: translateX(-5px); } 100% { transform: translateX(0); }}
a {color:#00bfff;text-decoration:none;display:block;text-align:center;margin-top:6px;}
a:hover {text-decoration:underline;}
.footer-text {text-align:center;font-size:13px;margin-top:25px;color:#ccc;letter-spacing:0.5px;}
.remember-me {color:#ccc;font-size:14px;margin-top:10px;text-align:left;max-width:300px;margin-left:auto;margin-right:auto;}
.captcha-box {cursor:pointer;user-select:none;text-align:center;font-weight:bold;color:#00bfff;font-size:20px;margin-bottom:10px;border:1px solid rgba(255,255,255,0.4);border-radius:8px;padding:8px 0;}
</style>
</head>
<body>

<div class="login-box">
<h2>Admin Login</h2>

<?php if (!empty($errorMessage)): ?>
<div class="error-text"><?php echo htmlentities($errorMessage); ?></div>
<?php endif; ?>

<form method="post" autocomplete="off">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

<div class="form-group">
<label for="username" class="label-text">Username</label>
<div class="input-group">
<i class="fa fa-user"></i>
<input type="text" id="username" class="form-control" name="username" placeholder="Enter your username" required value="<?php echo htmlspecialchars($uname); ?>">
</div>
</div>

<div class="form-group">
<label for="password" class="label-text">Password</label>
<div class="input-group">
<i class="fa fa-lock"></i>
<input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
<i class="fa fa-eye toggle-password" style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;"></i>
</div>
</div>

<div class="form-group">
<label class="label-text">Enter CAPTCHA:</label>
<div id="captcha" class="captcha-box"><?php echo $_SESSION['captcha']; ?></div>
<input type="text" class="form-control" name="captcha" placeholder="Enter CAPTCHA" required>
</div>

<div class="remember-me">
<label><input type="checkbox" name="remember" <?php if(isset($_COOKIE['remember_admin'])) echo 'checked'; ?>> Remember Me</label>
</div>

<button type="submit" class="btn btn-primary" name="submit">
Login <i class="fa fa-arrow-circle-right"></i>
</button>
</form>

<a href="../../index.php">Back to Home</a>
<div class="footer-text">IFM Hospital</div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    // Password toggle
    $(".toggle-password").click(function() {
        let input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    // Refresh CAPTCHA
    $("#captcha").click(function(){
        $.get("?refresh_captcha=1", function(data){
            $("#captcha").text(data);
        });
    });
});
</script>
</body>
</html>
