<?php
//  database connection
include '../student/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $professor_name = trim($_POST['professor_name']);
    $category = trim($_POST['category']);
    $email = trim($_POST['signup-email']);
    $username = trim($_POST['signup-username']);
    $password = trim($_POST['signup-password']);

    // password hashing for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $checkStmt = $conn->prepare("SELECT * FROM staff_profiles WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Username or email already exists
        echo "<script>alert('Username or Email already exists. Please choose a different one.');</script>";
    } else {
        // Prepare the SQL statement to insert the new staff profile
        $stmt = $conn->prepare("INSERT INTO staff_profiles (professor_name, category, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $professor_name, $category, $email, $username, $hashed_password);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Get the last inserted profile_id to use as a foreign key
            $profile_id = $conn->insert_id;

            // Now insert into 'professors' table using the new profile_id
            $profStmt = $conn->prepare("INSERT INTO professors (profile_id) VALUES (?)");
            $profStmt->bind_param("i", $profile_id);

            if ($profStmt->execute()) {
                // Successful signup, redirect to staff login
                $_SESSION['professor_name'] = $professor_name;
                $_SESSION['username'] = $username;
                header("Location: stafflogin.php");
                exit();
            } else {
                echo "<script>alert('Error signing up. Please try again.');</script>";
            }

            // Close the professor insert statement
            $profStmt->close();
        } else {
            // Handle error during staff profile insertion
            echo "<script>alert('Error signing up. Please try again.');</script>";
        }

        // Close the staff profile insert statement
        $stmt->close();
    }

    // Close the duplicate check statement
    $checkStmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Signup</title>
    <link rel="stylesheet" href="../css/staflogs.css"> <!-- Ensure the path to your CSS file is correct -->
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
            background-image: url('../images/staff.jpg'); /* Add your background image path here */
            background-repeat: no-repeat;
            background-size: cover; /* Use cover for better fit */
            background-position: center; /* Center the background image */
        }

        a{
            text-decoration: none !important;
            color: rgb(161, 98, 98);
            font-weight: bold !important;
            
        }
        a:hover{
            color: white !important;
        }

        p{
            font-size: large;
            font-weight: bold !important;;
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

    <!-- Signup Form Section -->
    <div class="form-container">
        <div class="form-box signup-box">
            <h2>Staff Signup</h2>
            <form id="signup-form" action="" method="POST"> <!-- Ensure the action points to your signup logic -->
                <div class="form-group">
                    <label for="signup-name">Full Name:</label>
                    <input type="text" id="signup-name" name="professor_name" required>
                </div>
                <div class="form-group">
                    <label for="signup-category">Category:</label>
                    <input type="text" id="signup-category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="signup-email">Email:</label>
                    <input type="email" id="signup-email" name="signup-email" required>
                </div>
                <div class="form-group">
                    <label for="signup-username">Username:</label>
                    <input type="text" id="signup-username" name="signup-username" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password:</label>
                    <input type="password" id="signup-password" name="signup-password" required>
                </div>
                <button type="submit" class="btn-login">Sign Up</button>
            </form>
            <p>Already have an account? <a href="../staff/stafflogin.php">Login</a></p>
        </div>
    </div>

    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>

</body>
</html>
