<?php
// Start sessio

// Include database connection file
include '../student/db.php';
include '../staff/stafffunct.php'; // Include additional functions if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $username = trim($_POST['login-username']);
    $password = trim($_POST['login-password']);

    // Prepare SQL statement to check for the user
    $stmt = $conn->prepare("SELECT profile_id, username, password FROM staff_profiles WHERE username = ?");
    
    if ($stmt === false) {
        die('SQL Prepare Error: ' . $conn->error);
    }

    // Bind the username to the prepared statement
    $stmt->bind_param("s", $username);
    
    if (!$stmt->execute()) {
        die('SQL Execute Error: ' . $stmt->error);
    }

    // Store the result to check if the user exists
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Bind result variables
        $stmt->bind_result($profile_id, $fetched_username, $stored_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $stored_password)) {
            // Set session variables
            $_SESSION['profile_id'] = $profile_id; // Use profile_id for referencing user-specific data
            $_SESSION['username'] = $fetched_username;

            // Redirect to the staff dashboard
            header("Location: dashstaff.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $stmt->close(); // Close the statement
}

// Close the database connection
$conn->close();
?>



<!-- You can display the error message (if any) in the HTML -->
<?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
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
            background-image: url('../images/staff.jpg'); 
            background-repeat: no-repeat;
            background-size: 100%;
            background-position: top center;
        }
        a{
            text-decoration: none !important;
            color: rgb(161, 98, 98);
            font-weight: bolder !important;
            font-size: large;
        }
        a:hover{
            color: white !important;
        }

        p{
            font-size: larger;
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
        </div>
        <ul class="nav-links">
            <li><a href="../enquiry system/index.php">Home</a></li>
            <li><a href="../enquiry system/getstarted.php">Get Started</a></li>
        </ul>
    </nav>

    <!-- Login Form Section -->
    <div class="form-container">
        <div class="form-box login-box">
            <h2>Staff Login</h2>
            <form id="login-form" action="" method="POST"> 
                <div class="form-group">
                    <label for="login-username">Username:</label>
                    <input type="text" id="login-username" name="login-username" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="login-password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <p>No account? <a href="signupstaff.php">Sign Up</a></p>
        </div>
    </div>
    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>

</body>
</html>
