<?php
// Include the database connection
include '../student/db.php'; 

// Start the session
session_start();

// Check if the professor is logged in
if (!isset($_SESSION['professor_name'])) {
    // Redirect to login page if not logged in
    header("Location: ../staff/stafflogin.php");
    exit();
}

// Get the professor's name from the session
$loggedInProfessor = $_SESSION['professor_name'];

// Get the profile_id from the session
$profile_id = $_SESSION['profile_id'] ?? null; // Ensure this is set during login

// Handle form submission for feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_feedback') {
    // Get form data and sanitize
    $title = trim($_POST['title']);
    $feedback = trim($_POST['feedback']);

    // Insert feedback into the database using prepared statements
    $sql = "INSERT INTO feedback (title, feedback, user_role, professor_id) VALUES (?, ?, 'staff', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $feedback, $profile_id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: ../feedback/feedstaff.php?message=Feedback sent successfully");
        exit(); // Ensure the script stops after redirection
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle deletion of feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_feedback') {
    $feedback_id = $_POST['feedback_id'];

    // Delete feedback from the database using prepared statements
    $delete_sql = "DELETE FROM feedback WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $feedback_id);

    if ($delete_stmt->execute()) {
        // Redirect with success message
        header("Location: feedstaff.php?message=Feedback deleted successfully");
        exit();
    } else {
        echo "Error deleting feedback: " . $delete_stmt->error;
    }
}

// Fetch replied feedback for the specific professor with user_role 'staff'
$sql_replied_feedback = "
    SELECT f.*, p.professor_id
    FROM feedback f 
    JOIN professors p ON f.professor_id = p.professor_id 
    WHERE f.user_role = 'staff' 
    AND f.response IS NOT NULL 
    AND f.professor_id = ?";

$stmt_replied_feedback = $conn->prepare($sql_replied_feedback);
$stmt_replied_feedback->bind_param("i", $profile_id);
$stmt_replied_feedback->execute();
$replied_feedback_result = $stmt_replied_feedback->get_result();

// Check for a success message in URL
$success_message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Panel - Send Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/feed.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar-container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="#">Staff Panel</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../enquiry system/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../enquiries/manage.php">Manage Enquiries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../resources/sentresource.php">Post Study Resources</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../resources/reviewsent.php">View Sent Resources</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Send Feedback to Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../staff/stafflogin.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Feedback Form Section -->
            <div class="col-lg-6">
                <section id="send-feedback">
                    <div class="card">
                        <div class="card-header">
                            <h2>Send Feedback to Admin</h2>
                        </div>
                        <div class="card-body">
                            <?php if ($success_message): ?>
                                <div class="alert alert-success"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="send_feedback">
                                <div class="form-group">
                                    <label for="feedback-title">Title:</label>
                                    <input type="text" id="feedback-title" name="title" class="form-control" placeholder="Enter feedback title" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="feedback">Feedback:</label>
                                    <textarea id="feedback" name="feedback" class="form-control" rows="4" placeholder="Write your feedback here..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Submit Feedback</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>

            <!-- View Feedback Section -->
            <div class="col-lg-6">
                <section id="view-feedback">
                    <div class="card">
                        <div class="card-header">
                            <h2>Replied Feedback</h2>
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php if ($replied_feedback_result->num_rows > 0): ?>
                                    <?php while ($feedback = $replied_feedback_result->fetch_assoc()): ?>
                                        <li>
                                            <b><?php echo htmlspecialchars($feedback['title']); ?></b>
                                            <p><?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?></p>
                                            <p><small>Response: <?php echo nl2br(htmlspecialchars($feedback['response'])); ?></small></p>
                                            <p><small>From: Admin <?php echo htmlspecialchars($feedback['professor_id']); ?> on <?php echo date('d-m-Y', strtotime($feedback['created_at'])); ?></small></p>
                                            <!-- Delete Button Form -->
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete_feedback">
                                                <input type="hidden" name="feedback_id" value="<?php echo htmlspecialchars($feedback['id']); ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</button>
                                            </form>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li>No replied feedback available.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="color: #fff;">
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
