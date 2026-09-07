<?php
include_once('hms/include/config.php');//it provides $con for database operations

// Contact form handling
if (isset($_POST['submit'])) {
    // sanitizing the inputs
    $name = trim(mysqli_real_escape_string($con, $_POST['fullname']));
    $email = trim(mysqli_real_escape_string($con, $_POST['emailid']));
    $mobileno = trim(mysqli_real_escape_string($con, $_POST['mobileno']));
    $dscrption = trim(mysqli_real_escape_string($con, $_POST['description']));

    // Validation
    if (empty($name) || empty($email) || empty($mobileno) || empty($dscrption)) {
        echo "<script>alert('Please fill all required fields.');</script>";
    } else {
        // prepared statement to protect against SQL injection
        $stmt = mysqli_prepare($con, "INSERT INTO tblcontactus (fullname, email, contactno, message) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $mobileno, $dscrption);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>alert('Thank you! Your message was successfully submitted.');</script>";
            echo "<script>window.location.href ='index.php'</script>";
            exit;
        } else {
            // fallback kama prepared stamenents fails
            $query = mysqli_query($con, "INSERT INTO tblcontactus(fullname,email,contactno,message) VALUES('$name','$email','$mobileno','$dscrption')");
            if ($query) {
                echo "<script>alert('Thank you! Your message was successfully submitted.');</script>";
                echo "<script>window.location.href ='index.php'</script>";
                exit;
            } else {
                echo "<script>alert('Submission failed, please try again later.');</script>";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IFM HOSPITAL — Patient & Clinic System</title>

    <!-- Fav and core CSS -->
    <link rel="shortcut icon" href="assets/images/fav.jpg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawsom-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />

    <style>
        /* Branding colors for IFM HOSPITAL */
        :root {
            --ifm-blue: #0b74c9;
            --ifm-green: #17a673;
            --muted: #6c757d;
            --card-radius: 12px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            color: #222;
            background: #f8fafc;
        }

        /* Header */
        .site-brand {
            font-weight: 700;
            color: var(--ifm-blue);
            font-size: 28px;
            letter-spacing: 0.4px;
        }
        .site-sub {
            font-size: 12px;
            color: var(--muted);
            display:block;
            margin-top: -4px;
        }

        /* Navigation */
        .header-nav {
            background: #fff;
            box-shadow: 0 2px 6px rgba(20,20,20,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-nav .nav-item ul { list-style: none; margin:0; padding:0; display:flex; gap: 18px; align-items:center; }
        .header-nav .nav-item a:hover { color: var(--ifm-blue); }

        /* Hero */
        .hero {
            background-image: linear-gradient(rgba(6,30,60,0.45), rgba(6,30,60,0.45)), url('assets/images/slider/slider_3.jpg');
            background-size: cover;
            background-position: center;        .header-nav .nav-item a { color: #333; text-decoration:none; padding: 12px 6px; display:inline-block; }

            padding: 70px 0;
            color: #fff;
        }
        .hero .hero-inner { max-width: 1140px; margin: 0 auto; display:flex; gap:30px; align-items:center; }
        .hero .hero-card { background: rgba(255,255,255,0.06); padding: 30px; border-radius: 10px; max-width:640px; }
        .hero h1 { font-size: 36px; margin-bottom: 12px; }
        .hero p.lead { font-size: 16px; color: #e6eefc; margin-bottom: 18px; max-width:600px; }

        /* Quick login cards */
        .login-card { border-radius: var(--card-radius); overflow: hidden; box-shadow: 0 8px 22px rgba(30,40,50,0.06); background:#fff; }
        .login-card img { width:100%; height:160px; object-fit:cover; display:block; }
        .login-card .card-body { padding:14px; }
        .login-card h6 { margin:6px 0 12px; font-weight:600; }

        /* Features */
        .key-features .single-key { background: #fff; padding: 22px; border-radius: 10px; box-shadow: 0 6px 18px rgba(20,20,20,0.04); text-align:center; margin-bottom: 18px; }
        .single-key i { font-size: 28px; color: var(--ifm-blue); margin-bottom:8px; display:block; }

        /* Contact */
        .contact-us-single { background:#fff; padding: 28px; border-radius: 10px; box-shadow: 0 10px 30px rgba(20,20,20,0.03); margin-top: 18px; }
        .contact-us-single label { margin-bottom:6px; font-weight:600; color:#333; }
        .contact-us-single .cf-ro { margin-bottom: 12px; }

        /* Footer */
        footer.footer { background:#0b74c9; color: #fff; padding: 28px 0; margin-top: 40px; }
        footer .link-list a { color: #fff; text-decoration:none; }
        .copy { background: #053a6b; color: #fff; padding: 10px 0; text-align:center; font-size:14px; }

        /* Responsiveness */
        @media (max-width: 767px) {
            .hero { padding: 36px 0; }
            .hero h1 { font-size: 24px; }
            .hero p.lead { font-size: 14px; }
        }
    </style>
</head>
<body>

<!-- =================== HEADER & HERO =================== -->
<header id="menu-jk">
    <div class="header-nav fixed-top">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <!-- Brand Logo -->
            <div class="brand-logo fw-bold" style="font-size:28px; color:#0b74c9; letter-spacing:1px;">
                IFM HOSPITAL<br>
                <span style="font-size:14px; font-weight:400; color:#6c757d;">Institute of Finance Management — Clinic</span>
            </div>

            <!-- Navigation -->
            <nav id="menu" class="d-none d-md-flex align-items-center justify-content-center">
                <ul class="nav list-unstyled mb-0">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about_us">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact_us">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="#logins">Logins</a></li>
                </ul>
            </nav>

            <!-- Book Appointment Button -->
            <div class="d-none d-lg-block">
                <a class="btn btn-success btn-lg" href="hms/user-login.php" 
                style="border-radius:30px; box-shadow:0 6px 20px rgba(0,0,0,0.3); transition:0.3s;">
                    Book Appointment
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <div class="d-inline d-md-none">
                <a class="btn btn-outline-primary btn-sm" data-toggle="collapse" data-target="#menu" href="#menu"><i class="fas fa-bars"></i></a>
            </div>
        </div>
    </div>
</header>

<!-- Hero -->
<section class="hero">
    <div class="hero-inner">
        <div style="flex:1;">
            <div class="hero-card">
                <h1>Welcome to IFM HOSPITAL</h1>
                <p class="lead">A student-built hospital management interface for learning and demonstration. We support appointment booking, patient record keeping and simple administrative reporting — built for small clinics and teaching hospitals in Tanzania.</p>

                <div class="d-flex gap-2">
                    <a href="hms/user-login.php" class="btn btn-light btn-lg mr-2">Patient Login</a>
                    <a href="hms/doctor" class="btn btn-outline-light btn-lg mr-2">Doctor Login</a>
                    <a href="hms/admin" class="btn btn-outline-light btn-lg">Admin Login</a>
                </div>

                <p style="margin-top:12px; color:#e6eefc;"><small><strong>Contact:</strong> +255 679 598 369 &nbsp; | &nbsp; info@ifmhospital.or.tz</small></p>
            </div>
        </div>
  <div class="card login-card position-relative overflow-hidden border-0 shadow-lg" style="height: 320px; border-radius: 18px;">
    <img src="assets/images/a.jpg" alt="IFM HOSPITAL front view"
         style="width:100%; height:100%; object-fit:cover; filter: brightness(60%); border-radius: 18px;">
    
    <!-- Overlay content -->
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center text-white">
      <h6 class="fw-bold mb-2">Quick Actions</h6>
      <p style="font-size:14px; color:rgba(255,255,255,0.9); margin-bottom: 20px;">
        Access common features quickly
      </p>
      <div class="d-flex gap-3">
        <a class="btn btn-success btn-sm px-3" href="hms/user-login.php">Book Appointment</a>
        <a class="btn btn-outline-light btn-sm px-3" href="#contact_us">Contact Us</a>
      </div>
    </div>
  </div>
</div>

    </div>
</section>

<!-- Logins Section -->
<section id="logins" class="our-blog container-fluid py-4">
    <div class="container">
        <div class="inner-title text-center mb-3">
            <h2>Logins</h2>
            <p class="text-muted">Choose your role to continue</p>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="login-card">
                    <img src="assets/images/patient.jpg" alt="Patient">
                    <div class="card-body">
                        <h6>Patient Login</h6>
                        <p style="font-size:13px; color:var(--muted);">Access your appointments and medical records.</p>
                        <a href="hms/user-login.php" class="btn btn-success btn-sm">Open</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="login-card">
                    <img src="assets/images/doctor.jpg" alt="Doctor">
                    <div class="card-body">
                        <h6>Doctor Login</h6>
                        <p style="font-size:13px; color:var(--muted);">View your schedule and patient history.</p>
                        <a href="hms/doctor" class="btn btn-success btn-sm">Open</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="login-card">
                    <img src="assets/images/admin.jpg" alt="Administrator">
                    <div class="card-body">
                        <h6>Admin Login</h6>
                        <p style="font-size:13px; color:var(--muted);">System management, reports & users.</p>
                        <a href="hms/admin" class="btn btn-success btn-sm">Open</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services or Key Features -->
<section id="services" class="key-features department py-4">
    <div class="container">
        <div class="inner-title text-center mb-3">
            <h2>Our Key Services</h2>
            <p class="text-muted">Basic services available at IFM HOSPITAL</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                    <i class="fas fa-heartbeat"></i>
                    <h5>General Clinic</h5>
                    <p style="font-size:13px; color:var(--muted);">Outpatient consultation and basic diagnostics.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                    <i class="fas fa-user-md"></i>
                    <h5>Medical Consultations</h5>
                    <p style="font-size:13px; color:var(--muted);">Consult with our qualified doctors and trainees.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                   <i class="fas fa-x-ray"></i>
                    <h5>Diagnostics</h5>
                    <p style="font-size:13px; color:var(--muted);">Laboratory and imaging support (basic).</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                    <i class="fas fa-pills"></i>
                    <h5>Pharmacy</h5>
                    <p style="font-size:13px; color:var(--muted);">Essential medications available on-site.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                    <i class="fas fa-ambulance"></i>
                    <h5>Emergency Care</h5>
                    <p style="font-size:13px; color:var(--muted);">Immediate attention for urgent cases.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="single-key">
                    <i class="far fa-thumbs-up"></i>
                    <h5>Quality Assurance</h5>
                    <p style="font-size:13px; color:var(--muted);">Student-led quality checks and audits.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us -->
<section id="about_us" class="about-us py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3">
                <img src="assets/images/b.jpg" alt="About IFM HOSPITAL" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h3>About IFM HOSPITAL</h3>
                <?php
                $ret = mysqli_query($con, "select * from tblpage where PageType='aboutus' ");
                while ($row = mysqli_fetch_array($ret)) {
                    $desc = strip_tags($row['PageDescription']);
                    echo "<p style='color:var(--muted);'>".htmlspecialchars($desc)."</p>";
                } 
                ?>
                <p style="margin-top:10px; color:var(--muted);"><small><strong>Location:</strong> Ohio Street, Posta — Dar es Salaam, Tanzania</small></p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery -->
<div id="gallery" class="gallery py-4">
    <div class="container">
        <div class="inner-title text-center mb-3">
            <h2>Our Gallery</h2>
            <p class="text-muted">Moments from IFM HOSPITAL</p>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <img src="assets/images/gallery/gallery_01.jpg" class="img-fluid rounded" alt="gallery image">
            </div>
            <div class="col-md-4 mb-3">
                <img src="assets/images/gallery/gallery_02.jpg" class="img-fluid rounded" alt="gallery image">
            </div>
            <div class="col-md-4 mb-3">
                <img src="assets/images/gallery/gallery_03.jpg" class="img-fluid rounded" alt="gallery image">
            </div>
        </div>
    </div>
</div>

<!-- Contact Form -->
<section id="contact_us" class="contact-us-single container my-4">
    <div class="row">
        <div class="col-md-7">
            <div style="padding:18px;">
                <h3>Contact IFM HOSPITAL</h3>
                <p style="color:var(--muted);">Have a question or need to book? Send us a message and our team will respond within 24 hours.</p>

                <form method="post" novalidate>
                    <div class="form-group row cf-ro">
                        <label class="col-sm-3 col-form-label">Enter Name :</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Full name" name="fullname" 
                            class="form-control input-sm" required>
                        </div>
                    </div>

                    <div class="form-group row cf-ro">
                        <label class="col-sm-3 col-form-label">Email Address :</label>
                        <div class="col-sm-9">
                            <input type="email" name="emailid" placeholder="name@example.com" 
                            class="form-control input-sm" required>
                        </div>
                    </div>

                    <div class="form-group row cf-ro">
                        <label class="col-sm-3 col-form-label">Mobile Number :</label>
                        <div class="col-sm-9">
                            <input type="tel" name="mobileno" placeholder="+255 7XX XXX XXX" class="form-control input-sm" required>
                        </div>
                    </div>

                    <div class="form-group row cf-ro">
                        <label class="col-sm-3 col-form-label">Message :</label>
                        <div class="col-sm-9">
                            <textarea rows="5" placeholder="Enter your message" class="form-control input-sm" name="description" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button class="btn btn-success btn-sm" type="submit" name="submit">Send Message</button>
                            <button class="btn btn-outline-secondary btn-sm" type="reset">Clear</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>

        <div class="col-md-5">
    <div style="padding:18px;">
        <h5>Contact Details</h5>
        <?php
        echo "<p style='color:var(--muted);'>IFM Hospital, providing quality healthcare services across Dar es Salaam.</p>";
        echo "<p style='color:var(--muted);'><strong>Phone:</strong> +255 67 959 8361</p>";
        echo "<p style='color:var(--muted);'><strong>Email:</strong> <a href='mailto:info@ifmhospital.co.tz'>info@ifmhospital.co.tz</a></p>";
        echo "<p style='color:var(--muted);'><strong>Opening Hours:</strong> Monday - Saturday, 8:00 AM to 6:00 PM</p>";
        ?>
        <hr>
        <h6>Visit Us</h6>
        <p style="color:var(--muted);">
            Posta,Ilala — Dar es Salaam, Tanzania
        </p>
    </div>
</div>

    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4>Useful Links</h4>
                <ul class="list-unstyled link-list">
                    <li><a href="#about_us">About us</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#services">Services</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#logins">Logins</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#gallery">Gallery</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#contact_us">Contact us</a><i class="fa fa-angle-right"></i></li>
                </ul>
            </div>

            <div class="col-md-6 col-sm-12">
                <h4>Contact Us</h4>
                <address class="md-margin-bottom-40" style="color:#fff;">
                    IFM HOSPITAL<br>
                    Ohio Street, Posta<br>
                    Dar es Salaam, Tanzania<br>
                    Phone: +255 679 598 369<br>
                    Email: <a href="mailto:info@ifmhospital.or.tz" style="color:#fff;">info@ifmhospital.or.tz</a><br>
                </address>
            </div>
        </div>
    </div>
</footer>
<div class="copy">
    <div class="container">
        © <?php echo date("Y"); ?> IFM HOSPITAL — Institute of Finance Management. All Rights Reserved.
    </div>
</div>

<!-- Scripts (project's JS files) -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/scroll-nav/js/jquery.easing.min.js"></script>
<script src="assets/plugins/scroll-nav/js/scrolling-nav.js"></script>
<script src="assets/plugins/scroll-fixed/jquery-scrolltofixed-min.js"></script>
<script src="assets/js/script.js"></script>

<script>
    // Smooth scroll for local anchors (improves UX)
    $(document).ready(function () {
        $('a[href^="#"]').on('click', function (e) {
            var target = this.hash;
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: $(target).offset().top - 60 }, 600);
            }
        });
    });
</script>

</body>
</html>
