<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Dashboard</title>

    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,600|Raleway:300,400,600|Poppins:400,500,600" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />

    <style>
        body {
            background: url('../assets/images/patienthands.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .container-fullw {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease-in-out;
        }

        .container-fullw:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.35);
        }

        .panel {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
            cursor: pointer;
        }

        .panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .panel-body {
            padding: 30px;
            color: #fff;
        }

        .panel-body .fa-stack {
            margin-bottom: 15px;
        }

        .panel-body h2 {
            font-weight: 600;
            color: #fff;
        }

        .panel-body p a {
            color: #00c6ff;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .panel-body p a:hover {
            color: #80e0ff;
        }

        .mainTitle {
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .breadcrumb {
            background: transparent;
            color: #ddd;
        }

        .breadcrumb li span {
            color: #f1f1f1;
        }

        .app-content {
            color: #fff;
        }
    </style>
</head>

<body>
<div id="app">
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>

        <div class="main-content">
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">User | Dashboard</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>User</span></li>
                            <li class="active"><span>Dashboard</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw">
                    <div class="row">

                        <!-- My Profile -->
                        <div class="col-sm-4">
                            <a href="edit-profile.php" style="text-decoration:none;">
                                <div class="panel text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">My Profile</h2>
                                        <p class="links cl-effect-1">Update Profile</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- My Appointments -->
                        <div class="col-sm-4">
                            <a href="appointment-history.php" style="text-decoration:none;">
                                <div class="panel text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-calendar-check-o fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">My Appointments</h2>
                                        <p class="links cl-effect-1">View Appointment History</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Book Appointment -->
                        <div class="col-sm-4">
                            <a href="book-appointment.php" style="text-decoration:none;">
                                <div class="panel text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-plus-circle fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">Book My Appointment</h2>
                                        <p class="links cl-effect-1">Book Appointment</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
    <?php include('include/setting.php'); ?>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-elements.js"></script>
<script>
    jQuery(document).ready(function() {
        Main.init();
        FormElements.init();
    });
</script>
</body>
</html>
