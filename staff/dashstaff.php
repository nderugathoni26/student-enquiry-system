<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: stafflogin.php");
    exit();
}
 // Get the username from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Student Enquiry Platform</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-image: url('../images/staffpanel.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        ::-webkit-scrollbar {
            display: none;
        }
        
        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgb(161, 98, 98);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 10px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background-color 0.3s, color 0.3s; 
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
        }
        

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
            flex: 1;
        }

        h1, h2, p {
            color: white;
            text-align: center;
        }

        .quote {
            font-style: italic;
            font-size: 1.2rem;
            color: #fffae3;
            margin-bottom: 30px;
        }

        footer {
            background-color: rgb(161, 98, 98);
            text-align: center;
            font-size: large;
            padding: 7px;
            width: 100%;
            margin-top: auto;
            color: white;
        }

        .section-overview {
            background-color: rgba(128, 128, 128, 0.6);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            margin-left: 20%;
            margin-right: 20%;
    
            
            color: black;
        }

        .section-overview h3 {
            color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 220px;
            }

            footer {
                margin-left: 200px;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div>
        <a class="navbar-brand " href="../staff/staffpanel.php"  style="font-size: 24px; font-weight: bold; color: #fff;">Staff Panel</a>
        <a href="../enquiry system/index.php">Home</a>
        <a class="nav-brand active" href="../staff/dashstaff.php">Dashboard</a>
        <a href="../enquiries/manage.php">Manage Enguiries</a>
        <a href="../resources/sentresource.php">post study resources</a>
        <a href="../resources/reviewsent.php">view sent resources</a>
        <a href="../feedback/feedbackstaff.php">send feedback to staff</a>
        <a href="../staff/stafflogin.php">Logout</a>
    </div>
    <footer>
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>
</div>


<div class="main-content" >
    <!-- Welcoming Section -->
    <section class="welcome-section">
        <h1>Welcome, !</h1>
        <p class="quote">"Education is the most powerful weapon which you can use to change the world." – Nelson Mandela</p>
    </section>

    <!-- Overview Section -->
    <section class="section-overview dispaly-flexbox">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="section-overview">
                    <h3>Student Enquiries</h3>
                    <p>Track your student enquiries, see which have been responded to.</p>
                    <a href="../enquiries/manage.php" class="btn btn-light mb-2">Student Enquiries</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="section-overview">
                    <h3>Upload study resources</h3>
                    <p>Upload new study resources for students to acces.</p>
                    <a href="../resources/sentresource.php" class="btn btn-light">Upload study Resources</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="section-overview">
                    <h3> Sent Resources</h3>
                    <p>view recently sent resources to avoid repeation </p>
                    <a href="../resources/reviewsent.php" class="btn btn-light">Sent Resources</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="section-overview">
                    <h3>feedback</h3>
                    <p>post feedbacks to the admin for any system issues.</p>
                    <a href="../feedback/feedstaff.php" class="btn btn-light">Feedbacks</a>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
