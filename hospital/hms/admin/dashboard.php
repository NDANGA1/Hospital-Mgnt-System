<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
} else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Dashboard</title>

    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />

    <style>
        body {
            background: url('../../assets/images/admin.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .panel a {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .panel:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
<div id="app">
<?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>

        <div class="main-content" >
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | Dashboard</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Dashboard</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                    <div class="row">

                        <!-- Manage Users -->
                        <div class="col-sm-4">
                            <a href="manage-users.php">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">Manage Users</h2>
                                        <p class="links cl-effect-1">
                                            <?php 
                                            $result = mysqli_query($con,"SELECT * FROM users");
                                            $num_rows = mysqli_num_rows($result);
                                            echo "Total Users: " . htmlentities($num_rows); 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Manage Doctors -->
                        <div class="col-sm-4">
                            <a href="manage-doctors.php">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">Manage Doctors</h2>
                                        <p class="cl-effect-1">
                                            <?php 
                                            $result1 = mysqli_query($con,"SELECT * FROM doctors");
                                            $num_rows1 = mysqli_num_rows($result1);
                                            echo "Total Doctors: " . htmlentities($num_rows1);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Appointments -->
                        <div class="col-sm-4">
                            <a href="appointment-history.php">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-terminal fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">Appointments</h2>
                                        <p class="links cl-effect-1">
                                            <?php 
                                            $sql= mysqli_query($con,"SELECT * FROM appointment");
                                            $num_rows2 = mysqli_num_rows($sql);
                                            echo "Total Appointments: " . htmlentities($num_rows2);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Manage Patients -->
                        <div class="col-sm-4">
                            <a href="manage-patient.php">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="fa fa-square fa-stack-2x text-primary"></i>
                                            <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">Manage Patients</h2>
                                        <p class="links cl-effect-1">
                                            <?php 
                                            $result = mysqli_query($con,"SELECT * FROM tblpatient");
                                            $num_rows = mysqli_num_rows($result);
                                            echo "Total Patients: " . htmlentities($num_rows);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- New Queries -->
                        <div class="col-sm-4">
                            <a href="unread-queries.php">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-2x">
                                            <i class="ti-files fa-1x text-primary"></i>
                                            <i class="fa fa-terminal fa-stack-1x fa-inverse"></i>
                                        </span>
                                        <h2 class="StepTitle">New Queries</h2>
                                        <p class="links cl-effect-1">
                                            <?php 
                                            $sql= mysqli_query($con,"SELECT * FROM tblcontactus where  IsRead is null");
                                            $num_rows22 = mysqli_num_rows($sql);
                                            echo "Total New Queries: " . htmlentities($num_rows22);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
<?php include('include/footer.php');?>
<?php include('include/setting.php');?>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="vendor/autosize/autosize.min.js"></script>
<script src="vendor/selectFx/classie.js"></script>
<script src="vendor/selectFx/selectFx.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
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
<?php } ?>
