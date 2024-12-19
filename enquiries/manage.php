<?php
session_start();  // Start the session at the beginning of the page

// Include your database connection
include '../student/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the professor is logged in
if (!isset($_SESSION['professor_name'])) {
    // Redirect to login page if not logged in
    header("Location: ../staff/stafflogin.php");
    exit();
}

$loggedInProfessor = $_SESSION['professor_name'];

// Handle form submission (Respond, Reject, or Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enquiryId = intval($_POST['enquiry_id']);  // Sanitize the enquiry ID
    $action = $_POST['action'];  // Either 'respond', 'reject', or 'delete'

    if ($action === 'respond') {
        // Handle response to the enquiry
        if (isset($_POST['response_text'])) {
            $responseText = $_POST['response_text'];
            $updateQuery = "UPDATE enquiries SET response = ?, status = 'Answered' WHERE id = ?"; // Changed status to 'Answered'

            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param('si', $responseText, $enquiryId);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Enquiry responded to successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error responding to the enquiry. Please try again.</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Response text is required.</div>";
        }
    } elseif ($action === 'reject') {
        // Handle rejection of the enquiry
        $rejectionReason = $_POST['reject_reason'];
        $updateQuery = "UPDATE enquiries SET rejection_reason = ?, status = 'Rejected' WHERE id = ?"; // Changed status to 'Rejected'
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('si', $rejectionReason, $enquiryId);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Enquiry rejected successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error rejecting the enquiry. Please try again.</div>";
        }
        $stmt->close();
    } elseif ($action === 'delete') {
        // Handle deletion of the enquiry
        $deleteQuery = $conn->prepare("DELETE FROM enquiries WHERE id = ?");
        $deleteQuery->bind_param("i", $enquiryId);
        if ($deleteQuery->execute()) {
            echo '<div class="alert alert-success">Enquiry deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting enquiry. Please try again later.</div>';
        }
        $deleteQuery->close();
    }
}

// Fetch all enquiries for the logged-in professor along with the student username
$sql = "
    SELECT e.id, e.enquiry_text, e.created_at, e.status, s.username, e.response, e.rejection_reason
    FROM enquiries e
    JOIN students s ON e.user_id = s.user_id
    WHERE e.professor_name = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInProfessor);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enquiries</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../css/manage.css" rel="stylesheet">
    <style>
        body {
    background-image: url('../images/staffpanel.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}
    </style>
</head>

<body>
<div class="navbar-container">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Staff Panel</a>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../enquiry system/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../staff/dashstaff.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../enquiries/manage.php">Manage Enquiries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  " href="../resources/sentresource.php">Post Study Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../resources/reviewsent.php">View Sent Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../feedback/feedstaff.php">Send Feedback to Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../staff/stafflogin.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

    <div class="container mt-5">
        <div class="card"> 
            <div class="card-header">
                <h1>Manage Enquiries</h1>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Student</th>
                            <th>Enquiry</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Respond</th>
                            <th>Reject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['enquiry_text']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td>
                                        <div style="display: flex; flex-direction: column;">
                                            <?php if ($row['status'] === 'Pending'): ?>
                                                <div id="response-form-<?php echo $row['id']; ?>" class="d-none">
                                                    <form method="POST" style="display:inline;">
                                                        <textarea name="response_text" placeholder="Enter response" required></textarea>
                                                        <input type="hidden" name="enquiry_id" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="action" value="respond">
                                                        <button type="submit" class="btn btn-success btn-sm">Submit Response</button>
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary btn-sm" type="button" onclick="toggleResponseForm(<?php echo $row['id']; ?>)">Reply</button>
                                            <?php else: ?>
                                             
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'Pending'): ?>
                                            <button class="btn btn-danger btn-sm" type="button" onclick="toggleRejectForm(<?php echo $row['id']; ?>)">Reject</button>
                                            <div id="reject-form-<?php echo $row['id']; ?>" class="d-none">
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="enquiry_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <input type="text" name="reject_reason" placeholder="Reason" required>
                                                    <button type="submit" class="btn btn-danger btn-sm">Submit Reason</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="enquiry_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to delete this enquiry?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No enquiries found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer style="color: #fff;">
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>
    <script src="../js/manage.js"> </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
