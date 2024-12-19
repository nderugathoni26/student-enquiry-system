<?php
// Include your database connection
include '../student/db.php'; // Adjust this to your db connection file

// Start session
session_start();

// Initialize variables for the status message
$statusMessage = '';
$statusClass = '';

// Check if user is logged in and get user ID
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to post an enquiry.');</script>";
    exit();
}
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

// Fetch pending and answered enquiries from the database
$pendingEnquiries = [];
$answeredEnquiries = [];

// SQL fetch enquiries
$sql = "SELECT professor_name, enquiry_text, created_at, status FROM enquiries WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'Pending') {
            $pendingEnquiries[] = $row;
        } else {
            $answeredEnquiries[] = $row;
        }
    }
}

// Fetch professor usernames from staff_profiles table
$professors = [];
$professorQuery = "SELECT professor_name FROM staff_profiles";
$professorResult = $conn->query($professorQuery);
if ($professorResult->num_rows > 0) {
    while ($profRow = $professorResult->fetch_assoc()) {
        $professors[] = $profRow['professor_name'];
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $professor_name = trim($_POST['professor_id']);
    $enquiry_text = trim($_POST['enquiry_text']);

    $stmt = $conn->prepare("INSERT INTO enquiries (user_id, category, professor_name, enquiry_text, created_at, status) VALUES (?, ?, ?, ?, NOW(), 'Pending')");
    $stmt->bind_param("isss", $user_id, $category, $professor_name, $enquiry_text);

    if ($stmt->execute()) {
        header("Location: pending.php");
        exit();
    } else {
        $statusMessage = 'Error submitting enquiry. Please try again later.';
        $statusClass = 'alert alert-danger';
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Enquiry</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/post.css">
</head>
<body>
<div class="navbar-container">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="../student/studentpanel.php">Student Panel</a>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../enquiry system/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../student/dashstudent.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="../enquiries/post.php">Post an Enquiry</a></li>
                <li class="nav-item"><a class="nav-link" href="../lecturers/lecturers.php">View Staff</a></li>
                <li class="nav-item"><a class="nav-link" href="../enquiries/pending.php">My Enquiries</a></li>
                <li class="nav-item"><a class="nav-link" href="../resources/viewresource.php">View Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="../messages/send.php">Message Peers</a></li>
                <li class="nav-item"><a class="nav-link" href="../messages/replymssg.php">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="../student/studentlogin.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Post an Enquiry</h2>
        </div>
        <div class="card-body">
            <form id="enquiryForm" method="POST">
                <div class="form-group">
                    <label for="category">Select Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Academic">Academic</option>
                        <option value="Exams">Exams</option>
                        <option value="Student Welfare">Student Welfare</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="professor_id">Select Professor:</label>
                    <select id="professor_id" name="professor_id" class="form-control" required>
                        <option value="">Select a professor</option>
                        <?php foreach ($professors as $professor): ?>
                            <option value="<?php echo htmlspecialchars($professor); ?>"><?php echo htmlspecialchars($professor); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="enquiry_text">Enquiry:</label>
                    <textarea id="enquiry_text" name="enquiry_text" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Enquiry</button>
            </form>
            <?php if ($statusMessage): ?>
                <div class="<?php echo $statusClass; ?>" role="alert">
                    <?php echo $statusMessage; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<footer style="color: #fff;">
    <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
