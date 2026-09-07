<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Doctor | Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Google Fonts -->
  <link href="http://fonts.googleapis.com/css?family=Lato:300,400,600,700|Raleway:300,400,500,600,700|Poppins:400,500,600&display=swap" rel="stylesheet">

  <!-- Core Vendor CSS -->
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
      background: url('../../assets/images/md.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', sans-serif;
      backdrop-filter: blur(4px);
    }

    .main-content {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      margin: 25px 30px;
      overflow: hidden;
    }

    h1.mainTitle {
      color: #fff;
      font-weight: 600;
      text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }

    .panel {
      border-radius: 20px !important;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      transition: all 0.3s ease;
      background-color: #fff !important;
      cursor: pointer;
      position: relative;
      text-align: center;
      height: 220px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }

    .panel:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }

    .panel a {
      position: absolute;
      inset: 0;
      z-index: 10;
    }

    .StepTitle {
      font-weight: 600;
      color: #333;
      z-index: 11;
    }

    .panel-body span.fa-stack {
      margin-bottom: 12px;
      z-index: 11;
    }

    .breadcrumb {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 10px;
      backdrop-filter: blur(8px);
      color: #fff;
    }

    .breadcrumb li,
    .breadcrumb li.active span {
      color: #fff;
    }

    /* Fix sidebar overlap by padding the content area */
    .wrap-content.container {
      padding-left: 30px;
      padding-right: 30px;
    }

    @media (max-width: 992px) {
      .panel {
        height: auto;
      }
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

          <!-- PAGE TITLE -->
          <section id="page-title">
            <div class="row">
              <div class="col-sm-8">
                <h1 class="mainTitle">Doctor | Dashboard</h1>
              </div>
              <ol class="breadcrumb">
                <li><span>User</span></li>
                <li class="active"><span>Dashboard</span></li>
              </ol>
            </div>
          </section>
          <!-- END PAGE TITLE -->

          <!-- MAIN CARDS -->
          <div class="container-fluid container-fullw">
            <div class="row">
              <div class="col-sm-4">
                <div class="panel panel-white no-radius">
                  <a href="edit-profile.php"></a>
                  <div class="panel-body">
                    <span class="fa-stack fa-2x"> 
                      <i class="fa fa-square fa-stack-2x text-primary"></i> 
                      <i class="fa fa-user-md fa-stack-1x fa-inverse"></i>
                    </span>
                    <h2 class="StepTitle">My Profile</h2>
                    <p>Update Profile Information</p>
                  </div>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="panel panel-white no-radius">
                  <a href="appointment-history.php"></a>
                  <div class="panel-body">
                    <span class="fa-stack fa-2x"> 
                      <i class="fa fa-square fa-stack-2x text-primary"></i> 
                      <i class="fa fa-calendar-check-o fa-stack-1x fa-inverse"></i> 
                    </span>
                    <h2 class="StepTitle">My Appointments</h2>
                    <p>View Appointment History</p>
                  </div>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="panel panel-white no-radius">
                  <a href="manage-patient.php"></a>
                  <div class="panel-body">
                    <span class="fa-stack fa-2x"> 
                      <i class="fa fa-square fa-stack-2x text-primary"></i> 
                      <i class="fa fa-file-text-o fa-stack-1x fa-inverse"></i> 
                    </span>
                    <h2 class="StepTitle">Patient Records</h2>
                    <p>View and Manage Patients</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END MAIN CARDS -->

        </div>
      </div>
    </div>

    <!-- FOOTER -->
    <?php include('include/footer.php'); ?>
    <?php include('include/setting.php'); ?>
  </div>

  <!-- JS FILES -->
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
<?php } ?>
