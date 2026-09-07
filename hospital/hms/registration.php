<?php
include_once('include/config.php');
$showToast = false;
if (isset($_POST['submit'])) {
    $fname = $_POST['full_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($con, "INSERT INTO users(fullname,address,city,gender,email,password) 
                                VALUES('$fname','$address','$city','$gender','$email','$password')");
    if ($query) {
        $showToast = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

    <style>
        body {
            background: url('/EDITING hms/hospital/assets/images/pp.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-container {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
            width: 500px;
            padding: 40px;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: #0056b3;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 8px;
            height: 45px;
            border: 1px solid #ccc;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 6px rgba(0,123,255,0.3);
        }

        label {
            font-weight: 500;
        }

        .btn-primary {
            width: 100%;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            background-color: #007bff;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .gender-options {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }

        .gender-options label {
            margin-left: 5px;
            font-weight: normal;
        }

        .copyright {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 15px;
        }

        /* Toast */
        #toast-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
            font-weight: 500;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @keyframes fadein {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @keyframes fadeout {
            from {opacity: 1;}
            to {opacity: 0;}
        }
    </style>

    <script type="text/javascript">
        function valid() {
            if (document.registration.password.value != document.registration.password_again.value) {
                alert("Password and Confirm Password fields do not match!");
                document.registration.password_again.focus();
                return false;
            }
            return true;
        }

        function userAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data:'email='+$("#email").val(),
                type: "POST",
                success:function(data){
                    $("#user-availability-status1").html(data);
                    $("#loaderIcon").hide();
                },
                error:function (){}
            });
        }
    </script>
</head>
<body>
    <?php if($showToast): ?>
    <div id="toast-success">Successfully Registered! Redirecting to login...</div>
    <script>
        setTimeout(() => { window.location.href = 'user-login.php'; }, 3000);
    </script>
    <?php endif; ?>

    <div class="register-container">
        <h2>Patient Registration</h2>
        <form name="registration" id="registration" method="post" onsubmit="return valid();">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" class="form-control" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="mb-3">
                <label>Address</label>
                <input type="text" class="form-control" name="address" placeholder="Your address" required>
            </div>

            <div class="mb-3">
                <label>City</label>
                <input type="text" class="form-control" name="city" placeholder="City name" required>
            </div>

            <div class="mb-3">
                <label>Gender</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="male" required> Male</label>
                    <label><input type="radio" name="gender" value="female"> Female</label>
                </div>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="email" onblur="userAvailability()" placeholder="example@email.com" required>
                <span id="user-availability-status1" style="font-size:12px;"></span>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Create password" required>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="password_again" placeholder="Re-enter password" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="agree" checked readonly>
                <label for="agree" class="form-check-label">I agree to the terms</label>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Register</button>

            <div class="login-link">
                Already have an account? <a href="user-login.php">Login here</a>
            </div>

            <div class="copyright">
                &copy; <span class="current-year"></span> Hospital Management System. All rights reserved.
            </div>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
