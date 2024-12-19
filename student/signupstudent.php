<?php
// database connection
include '../student/db.php'; 

// Initialize variables for status messages
$statusMessage = '';
$statusClass = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $username = trim($_POST['signup-username']);
    $email = trim($_POST['signup-email']);
    $password = trim($_POST['signup-password']);

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Validate for duplicate username or email
    $stmt = $conn->prepare("SELECT * FROM students WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username or email already exists
        $statusMessage = 'Username or Email already exists. Please choose a different one.';
        $statusClass = 'alert alert-danger';
    } else {
        // Prepare and execute the SQL query to insert student data
        $insertStmt = $conn->prepare("INSERT INTO students (username, email, password) VALUES (?, ?, ?)");
        $insertStmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($insertStmt->execute()) {
            // Signup successful - Redirect to the login page
            header('Location: studentlogin.php');
            exit(); // Ensure no further code is executed
        } else {
            $statusMessage = 'Error during signup. Please try again later.';
            $statusClass = 'alert alert-danger';
        }

        $insertStmt->close();
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup</title>
    <link rel="stylesheet" href="../css/student-login-signup.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../images/student.jpg'); 
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top center;
        }

        a {
            text-decoration: none !important;
            color: rgb(161, 98, 98);
            font-weight: bolder !important;
            font-size: large;
        }
        a:hover {
            color: white !important;
        }

        p {
            font-size: larger;
            font-weight: bold !important;
        }

        .separator {
            height: 20px;
            width: 100%;
            background-color: transparent; 
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <a href="../enquiry system/index.php" style="text-decoration: none; color: inherit;">
                <h1>StudyConnect</h1>
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="../enquiry system/index.php">Home</a></li>
            <li><a href="../enquiry system/getstarted.php">Get Started</a></li>
        </ul>
    </nav>

    <!-- Signup Section -->
    <div class="form-container">
        <div class="form-box signup-box">
            <h2>Student Signup</h2>
            <?php if (!empty($statusMessage)) { ?>
                <div class="<?php echo $statusClass; ?>"><?php echo $statusMessage; ?></div>
            <?php } ?>
            <form id="signup-form" method="POST" action="">
                <div class="form-group">
                    <label for="signup-username">Username:</label>
                    <input type="text" id="signup-username" name="signup-username" required>
                </div>
                <div class="form-group">
                    <label for="signup-email">Email:</label>
                    <input type="email" id="signup-email" name="signup-email" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password:</label>
                    <input type="password" id="signup-password" name="signup-password" required>
                </div>
                <button type="submit" class="btn-login">Sign Up</button>
                <p>Already signed in? <a href="studentlogin.php"> Login </a></p>
            </form>
        </div>
    </div>

    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>

    <script src="../javascript/toggle.js"></script>
</body>
</html>