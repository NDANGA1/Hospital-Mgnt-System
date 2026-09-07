<?php
session_start();
include("include/config.php");

$errorMessage = '';
$uname = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $uname = trim($_POST['username']);
    $dpassword = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM doctors WHERE docEmail=? LIMIT 1");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->fetch_assoc();

    if ($num) {
        $dbPass = $num['password'];

        if (strlen($dbPass) == 32 && md5($dpassword) === $dbPass) {
            $newHash = password_hash($dpassword, PASSWORD_BCRYPT);
            $updateStmt = $con->prepare("UPDATE doctors SET password=? WHERE id=?");
            $updateStmt->bind_param("si", $newHash, $num['id']);
            $updateStmt->execute();
            $dbPass = $newHash;
        }

        if (password_verify($dpassword, $dbPass)) {
            session_regenerate_id(true);
            $_SESSION['dlogin'] = $uname;
            $_SESSION['id'] = $num['id'];

            $uid = $num['id'];
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 1;

            $logStmt = $con->prepare("INSERT INTO doctorslog(uid, username, userip, status) VALUES (?, ?, ?, ?)");
            $logStmt->bind_param("issi", $uid, $uname, $uip, $status);
            $logStmt->execute();

            header("location:dashboard.php");
            exit;
        } else {
            $uip = $_SERVER['REMOTE_ADDR'];
            $status = 0;
            $logStmt = $con->prepare("INSERT INTO doctorslog(username, userip, status) VALUES (?, ?, ?)");
            $logStmt->bind_param("ssi", $uname, $uip, $status);
            $logStmt->execute();

            $errorMessage = "Invalid username or password";
        }
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        $logStmt = $con->prepare("INSERT INTO doctorslog(username, userip, status) VALUES (?, ?, ?)");
        $logStmt->bind_param("ssi", $uname, $uip, $status);
        $logStmt->execute();

        $errorMessage = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Login</title>
<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../../vendor/fontawesome/css/font-awesome.min.css">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: url('../../assets/images/blackdoctors.jpg') no-repeat center center fixed;
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

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

.login-box h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #00bfff;
    font-weight: 700;
    letter-spacing: 1px;
}

.form-group {
    margin-bottom: 20px;
    text-align: center;
}

.label-text {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #00bfff;
    font-size: 15px;
    text-align: left;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

.input-group {
    position: relative;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

.input-group i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #00bfff;
    font-size: 16px;
}

.input-group .toggle-password {
    right: 15px;
    left: auto;
    cursor: pointer;
}

.form-control,
.btn-primary,
.error-text {
    width: 100%;
    max-width: 300px;
    padding: 14px 14px 14px 45px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.4);
    background-color: rgba(255,255,255,0.12);
    color: #fff;
    font-size: 15px;
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
    margin: 0 auto;
    display: block;
}

.form-control::placeholder { color: rgba(255,255,255,0.7); }

.form-control:focus {
    border-color: #00bfff;
    box-shadow: 0 0 12px rgba(0,191,255,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
    background-color: rgba(255,255,255,0.18);
    outline: none;
    color: #fff;
}

.btn-primary {
    font-weight: 600;
    background: linear-gradient(135deg,#00bfff,#0080c0);
    border: none;
    transition: all 0.4s ease;
    margin-top: 10px;
    font-size: 16px;
}

.btn-primary:hover {
    background: linear-gradient(135deg,#0080c0,#005f99);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.5);
}

.error-text {
    color: #ff4d4d;
    font-weight: 500;
    text-align: center;
    margin-bottom: 15px;
    padding: 0;
    background: none;
    border: none;
    box-shadow: none;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

a {
    color: #00bfff;
    text-decoration: none;
    display: block;
    text-align: center;
    margin-top: 6px;
}

a:hover {
    text-decoration: underline;
}

.footer-text {
    text-align: center;
    font-size: 13px;
    margin-top: 25px;
    color: #ccc;
    letter-spacing: 0.5px;
}
</style>
</head>
<body>

<div class="login-box">
    <h2>Doctor Login</h2>

    <?php if($errorMessage != ''): ?>
        <div class="error-text"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <div class="form-group">
            <label for="username" class="label-text">Email</label>
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" id="username" class="form-control" name="username" placeholder="Enter your email" required autocomplete="off" value="<?php echo htmlspecialchars($uname); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="label-text">Password</label>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required autocomplete="new-password">
                <i class="fa fa-eye toggle-password" aria-label="Toggle password visibility"></i>
            </div>
            <a href="forgot-password.php">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">
            Login <i class="fa fa-arrow-circle-right"></i>
        </button>
    </form>

    <div class="footer-text">IFM Hospital</div>
</div>

<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.toggle-password').on('click', function() {
        const input = $('#password');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('.toggle-password').on('click', function() {
        const input = $('#password');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });
});
</script>

</body>
</html>