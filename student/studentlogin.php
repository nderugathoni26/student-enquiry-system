<?php
// Start session


include '../student/db.php'; // Database connection file
include 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $username = trim($_POST['login-username']);
    $password = trim($_POST['login-password']);

    // Prepare SQL statement to fetch username and password hash
    $stmt = $conn->prepare("SELECT user_id, username, password FROM students WHERE username = ?");
    
    if ($stmt === false) {
        die('SQL Prepare Error: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);
    
    if (!$stmt->execute()) {
        die('SQL Execute Error: ' . $stmt->error);
    }

    $stmt->store_result(); // Store the result to check if the user exists

    if ($stmt->num_rows == 1) {
        // Bind result variables
        $stmt->bind_result($userId, $fetchedUsername, $stored_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $stored_password)) {
            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $fetchedUsername;
            
            header("Location: dashstudent.php"); // Redirect to student page
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that username.');</script>";
    }

    $stmt->close(); // Close the statement
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="../css/student-login-signup.css"> <!-- Ensure the path to your CSS file is correct -->
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
            background-image: url('../images/student.jpg'); /* Ensure the path to your image is correct */
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

    <!-- Login Section -->
    <div class="form-container">
        <div class="form-box login-box">
            <h2>Student Login</h2>
            <form id="login-form" method="POST" action="">
                <div class="form-group">
                    <label for="login-username">Username:</label>
                    <input type="text" id="login-username" name="login-username" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="login-password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
                <p>No account? <a href="signupstudent.php">Signup</a></p>
            </form>
        </div>
    </div>

    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>

</body>
</html>
