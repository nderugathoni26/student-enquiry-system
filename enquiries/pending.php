<?php
// Include your database connection
include '../student/db.php'; // Adjust this to your db connection file

// Start the session loged in use
session_start();
$userId = $_SESSION['user_id']; // Ensure the user_id is stored in session after login

// Initialize variables for the status message
$statusMessage = '';
$statusClass = '';

// Check if a delete request has been submitted
if (isset($_POST['delete'])) {
    // Get the enquiry ID from the form
    $enquiryId = intval($_POST['delete_enquiry_id']);
    
    //  DELETE query
    $deleteQuery = $conn->prepare("DELETE FROM enquiries WHERE id = ? AND user_id = ?");
    $deleteQuery->bind_param("ii", $enquiryId, $userId); // Ensure that the user_id matches

    // Execute delete query
    if ($deleteQuery->execute()) {
        // Success message after deletion
        $statusMessage = 'Enquiry deleted successfully!';
        $statusClass = 'alert alert-success';
    } else {
        // Error message for delete operation  fails
        $statusMessage = 'Error deleting enquiry. Please try again later.';
        $statusClass = 'alert alert-danger';
    }

    // Close the prepared statement
    $deleteQuery->close();
}

// Fetch pending and responded 
$pendingEnquiries = [];
$respondedEnquiries = [];

// SQL query to fetch pending enquiries for the logged-in user
$sqlPending = "SELECT id, professor_name, enquiry_text, created_at, status 
               FROM enquiries 
               WHERE status = 'Pending' AND user_id = ?";
$pendingStmt = $conn->prepare($sqlPending);
$pendingStmt->bind_param("i", $userId);
$pendingStmt->execute();
$resultPending = $pendingStmt->get_result();

if ($resultPending) {
    while ($row = $resultPending->fetch_assoc()) {
        $pendingEnquiries[] = $row;
    }
}

// SQL query to fetch responded enquiries for the logged-in user
$sqlResponded = "SELECT id, professor_name, enquiry_text, created_at, status, response, rejection_reason 
                 FROM enquiries 
                 WHERE status IN ('Answered', 'Rejected') AND user_id = ?";
$respondedStmt = $conn->prepare($sqlResponded);
$respondedStmt->bind_param("i", $userId);
$respondedStmt->execute();
$resultResponded = $respondedStmt->get_result();

if ($resultResponded) {
    while ($row = $resultResponded->fetch_assoc()) {
        $respondedEnquiries[] = $row;
    }
}

// Close the prepared statements
$pendingStmt->close();
$respondedStmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiries Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/pending.css">
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
                <li class="nav-item"><a class="nav-link" href="../enquiries/post.php">Post an Enquiry</a></li>
                <li class="nav-item"><a class="nav-link" href="../lecturers/lecturers.php">View Staff</a></li>
                <li class="nav-item"><a class="nav-link active" href="../enquiries/pending.php">My Enquiries</a></li>
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
            <h2> My Enquiries </h2>
        </div>
        <div class="card-body">
            <!-- Status Message -->
            <div class="<?php echo $statusClass; ?> mt-3" id="statusMessage">
                <?php echo $statusMessage; ?>
            </div>

            <!-- Pending Enquiries Table -->
            <!-- Pending Enquiries Table -->
<h3>Pending Enquiries</h3>
<table class="table table-hover">
    <thead class="thead-dark">
        <tr>
            <th>Professor</th>
            <th>Enquiry</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($pendingEnquiries)): ?>
            <tr>
                <td colspan="5">No pending enquiries found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($pendingEnquiries as $enquiry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($enquiry['professor_name']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['enquiry_text']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['status']); ?></td>
                    <td>
                       
                        <form action="pending.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_enquiry_id" value="<?php echo $enquiry['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- Responded Enquiries Table -->
<h3>Responded Enquiries</h3>
<table class="table table-hover">
    <thead class="thead-dark">
        <tr>
            <th>Professor</th>
            <th>Enquiry</th>
            <th>Response</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($respondedEnquiries)): ?>
            <tr>
                <td colspan="6">No responded enquiries found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($respondedEnquiries as $enquiry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($enquiry['professor_name']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['enquiry_text']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['response'] ? $enquiry['response'] : $enquiry['rejection_reason']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($enquiry['status']); ?></td>
                    <td>
                        <form action="pending.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_enquiry_id" value="<?php echo $enquiry['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

        </div>
    </div>
</div>

<!-- Footer -->
<footer style="color: #fff;">
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
