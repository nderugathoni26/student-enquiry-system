<?php
// Start the session
include '../student/db.php';
include '../student/function.php';

// Hardcoded admin credentials (for demo purposes, better to store in a database)
$adminUsername = "admin";
$adminPassword = "Admin123"; // You should hash this password in a real implementation

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate the admin login
    if ($username === $adminUsername && $password === $adminPassword) {
        // Successful login, create session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        // Redirect to the admin dashboard
        header("Location: admDash.php");
        exit();
    } else {
        // Invalid credentials, display an error message
        $loginError = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

    <!-- Navigation Bar -->
   <header>
    <nav>
    <div class="logo">
        <a href="../enquiry system/index.php" style="text-decoration: none; color: inherit;">
        <h1>StudyConnect</h1>
    </div>
    <ul class="nav-links">
        <li><a href="../enquiry system/index.php">Home</a></li>
        <li><a href="../enquiry system/getstarted.php">Get started</a></li>
    </ul>
</nav>
   </header>

    <!-- Admin Login Section -->
    <section id="admin-login">
        <div class="container">
            <h2>Admin Login</h2>
            <p class="description">Please enter your admin credentials to continue:</p>

            <form class="login-form" id="adminLoginForm" action="" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>

</body>
</html>
