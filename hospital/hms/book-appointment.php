<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

if(isset($_POST['ajaxRequest']))
{
    include('include/config.php');
    session_start();

    $specilization = $_POST['Doctorspecialization'];
    $doctorid      = $_POST['doctor'];
    $userid        = $_SESSION['id'];
    $fees          = $_POST['fees'];
    $appdate       = $_POST['appdate'];
    $time          = $_POST['apptime'];
    $userstatus    = 1;
    $docstatus     = 1;

    $today = date('Y-m-d');

    /* Check that appointment date is not in the past */
    if($appdate < $today){
        echo "error|You cannot book an appointment in the past.";
        exit();
    }

    /* Check doctor availability */
    $doctorCheck = mysqli_query($con,
        "SELECT id FROM appointment 
         WHERE doctorId='$doctorid'
         AND appointmentDate='$appdate'
         AND appointmentTime='$time'
         AND doctorStatus=1");

    if(mysqli_num_rows($doctorCheck) > 0){
        echo "error|Doctor is not available at this time.";
        exit();
    }

    /* Check patient availability at same time */
    $patientTimeCheck = mysqli_query($con,
        "SELECT id FROM appointment
         WHERE userId='$userid'
         AND appointmentDate='$appdate'
         AND appointmentTime='$time'
         AND userStatus=1");

    if(mysqli_num_rows($patientTimeCheck) > 0){
        echo "error|You already have another appointment at this time.";
        exit();
    }

    /* Prevent same patient booking same doctor same day */
    $sameDoctorSameDay = mysqli_query($con,
        "SELECT id FROM appointment
         WHERE userId='$userid'
         AND doctorId='$doctorid'
         AND appointmentDate='$appdate'
         AND userStatus=1");

    if(mysqli_num_rows($sameDoctorSameDay) > 0){
        echo "error|You already booked this doctor on this day.";
        exit();
    }

    /* Insert appointment */
    $query = mysqli_query($con,
        "INSERT INTO appointment
        (doctorSpecialization,doctorId,userId,consultancyFees,
         appointmentDate,appointmentTime,userStatus,doctorStatus)
        VALUES
        ('$specilization','$doctorid','$userid','$fees',
         '$appdate','$time','$userstatus','$docstatus')");

    if($query){
        echo "success|Appointment successfully booked.";
    } else {
        echo "error|Unable to process request.";
    }

    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>User  | Book Appointment</title>
	
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
		<script>
function getdoctor(val) {
	$.ajax({
	type: "POST",
	url: "get_doctor.php",
	data:'specilizationid='+val,
	success: function(data){
		$("#doctor").html(data);
	}
	});
}
</script>	


<script>
function getfee(val) {
	$.ajax({
	type: "POST",
	url: "get_doctor.php",
	data:'doctor='+val,
	success: function(data){
		$("#fees").html(data);
	}
	});
}
</script>	




	</head>
	<body>
		<div id="app">		
<?php include('include/sidebar.php');?>
			<div class="app-content">
			
						<?php include('include/header.php');?>
					
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">User | Book Appointment</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span>User</span>
									</li>
									<li class="active">
										<span>Book Appointment</span>
									</li>
								</ol>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									
									<div class="row margin-top-30">
										<div class="col-lg-8 col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
													<h5 class="panel-title">Book Appointment</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg1']);?>
								<?php echo htmlentities($_SESSION['msg1']="");?></p>	
								
								
								<form role="form" id="appointmentForm">
														


<div class="form-group">
															<label for="DoctorSpecialization">
																Doctor Specialization
															</label>
							<select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required="required">
																<option value="">Select Specialization</option>
<?php $ret=mysqli_query($con,"select * from doctorspecilization");
while($row=mysqli_fetch_array($ret))
{
?>
																<option value="<?php echo htmlentities($row['specilization']);?>">
																	<?php echo htmlentities($row['specilization']);?>
																</option>
																<?php } ?>
																
															</select>
														</div>




														<div class="form-group">
															<label for="doctor">
																Doctors
															</label>
						<select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value);" required="required">
						<option value="">Select Doctor</option>
						</select>
														</div>





														<div class="form-group">
															<label for="consultancyfees">
																Consultancy Fees
															</label>
					<select name="fees" class="form-control" id="fees"  readonly>
						
						</select>
														</div>
														
<div class="form-group">
															<label for="AppointmentDate">
																Date
															</label>
<input class="form-control datepicker" name="appdate"  required="required" data-date-format="yyyy-mm-dd">
	
														</div>
														
<div class="form-group">
															<label for="Appointmenttime">
														
														Time
													
															</label>
			<input class="form-control" name="apptime" id="timepicker1" required="required">eg : 10:00 PM
														</div>														
														
														<button type="submit" class="btn btn-primary">
															Submit
														</button>
													</form>
												</div>
											</div>
										</div>
											
											</div>
										</div>
									
									</div>
								</div>
							
						<!-- end: BASIC EXAMPLE -->
			
					
					
						
						
					
						<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			</div>
			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
			<!-- end: SETTINGS -->
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});

			$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    startDate: new Date()
});
		</script>
		  <script type="text/javascript">
            $('#timepicker1').timepicker();
        </script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->


<!-- Booking Result Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Appointment Status</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center" id="modalBody" style="font-size:16px;">
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function(){

    $("#appointmentForm").on("submit", function(e){
        e.preventDefault();

        var formData = $(this).serialize() + "&ajaxRequest=1";

        $.ajax({
            type: "POST",
            url: "",
            data: formData,
            success: function(response){

                var parts = response.split("|");
                var status = parts[0];
                var message = parts[1];

                $("#modalBody").html(message);

                if(status === "success"){

                    $("#modalTitle").text("Booking Successful");
                    $(".modal-header").removeClass("bg-danger").addClass("bg-success");

                    $("#bookingModal").modal("show");

                    /* Page refresh only when booking is successful */
                    setTimeout(function(){
                        location.reload();
                    }, 2000);

                } else {

                    $("#modalTitle").text("Booking Failed");
                    $(".modal-header").removeClass("bg-success").addClass("bg-danger");

                    $("#bookingModal").modal("show");

                }
            },
            error: function(){
                $("#modalTitle").text("System Error");
                $("#modalBody").html("Something went wrong. Please try again.");
                $(".modal-header").removeClass("bg-success").addClass("bg-danger");
                $("#bookingModal").modal("show");
            }
        });

    });

});
</script>

	</body>
</html>
