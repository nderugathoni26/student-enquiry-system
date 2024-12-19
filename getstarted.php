<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started</title>
    <link rel="stylesheet" href="../css/get.css">
    <style>
        /* Get Started Section */
#get-started {
    padding: 95px 10px 50px;
    background-color: #f4f4f4;
    text-align: center;
    font-family: 'Arial', sans-serif;
    margin-top: 70px; 

    /* Background Image */
    background-image: url('../images/get.jpg'); 
    background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat; 
    position: relative; 

    /* Optional: Overlay Effect */
    background-blend-mode: overlay;
    background-color: rgba(0, 0, 0, 0.5); 
    color: white; 
}


    </style>
</head>
<body>
    <div id="loader" class="loader"></div>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <a href="../enquiry system/index.php" style="text-decoration: none; color: inherit;">
            <h1>StudyConnect</h1>
        </div>
        <ul class="nav-links">
            <li><a href="../enquiry system/index.php">Home</a></li>
            <li><a href="#get-started">Get Started</a></li>
            <li><a href="#reviews">Reviews</a></li>
        </ul>
    </nav>

    <!-- Get Started Section -->
    <section id="get-started" class="full-screen-section">
        <div class="container">
            <h2>Get Started</h2>
            <p class="description">Please choose your role to continue:</p>

            <div class="role-cards">
                <!-- Admin Card -->
                <div class="role-card">
                    <h3>Admin</h3>
                    <p>Manage the platform, review feedback, and access administrative tools.</p>
                    <a href="../admin/adminlogin.php" class="btn-role">Continue as Admin</a>
                </div>

                <!-- Student Card -->
                <div class="role-card">
                    <h3>Student</h3>
                    <p>Post inquiries, access resources, and communicate with peers.</p>
                    <a href="../student/signupstudent.php" class="btn-role">Continue as Student</a>
                </div>

                <!-- Staff Card -->
                <div class="role-card">
                    <h3>Staff</h3>
                    <p>Respond to inquiries, manage resources, and engage with students.</p>
                    <a href="../staff/signupstaff.php" class="btn-role">Continue as Staff</a>
                </div>

                
            </div>
        </div>
    </section>

   

    <!-- Reviews Section -->
    <section class="reviews" id="reviews">
        <div class="container">
            <h2>What People Are Saying</h2>
            <div class="review-cards">
                <!-- Review Card 1 -->
                <div class="review-card">
                    <h3>Faith Nzilani</h3>
                    <div class="rating">
                        <span>★★★★☆</span>
                    </div>
                    <p class="feedback">This platform has been incredibly helpful in connecting with professors and accessing resources. The peer study group feature is amazing!</p>
                </div>

                <!-- Review Card 2 -->
                <div class="review-card">
                    <h3>Jane Samuel</h3>
                    <div class="rating">
                        <span>★★★★★</span>
                    </div>
                    <p class="feedback">I love how organized everything is. I can easily post my questions and get timely responses. Highly recommend to all students!</p>
                </div>

                <!-- Review Card 3 -->
                <div class="review-card">
                    <h3>Alex Kamande</h3>
                    <div class="rating">
                        <span>★★★☆☆</span>
                    </div>
                    <p class="feedback">The platform is good, but I feel the response time could be improved. Overall, it’s a great resource for students.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>

<script src="loader.js"></script>
</body>
</html>
