<?php
include '../student/db.php'; // Database connection

// Fetch feedback records and include professor's name
$sql = "SELECT feedback.*, staff_profiles.professor_name 
        FROM feedback 
        JOIN professors ON feedback.professor_id = professors.professor_id 
        JOIN staff_profiles ON professors.profile_id = staff_profiles.profile_id 
        WHERE feedback.user_role = 'staff'";
$result = $conn->query($sql);

// Handle Delete Functionality
if (isset($_POST['delete_feedback_id'])) {
    $id = (int)$_POST['delete_feedback_id']; // Cast to int for security

    // Delete query to remove feedback record
    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Feedback deleted successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    exit(); // Make sure to exit to prevent the rest of the page from executing
}

// Handle Reply Functionality
if (isset($_POST['reply_feedback_id'])) {
    $id = $_POST['reply_feedback_id'];
    $response = $conn->real_escape_string($_POST['response']); // Sanitize input

    // Update query to add the admin's response to the feedback
    $sql = "UPDATE feedback SET response = '$response' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the feedback management page after a successful update
        header("Location: Adfeed.php"); // Change to your feedback management page
        exit();
    } else {
        // Handle the error if needed (you can redirect to an error page or display an error message)
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Feedback</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/adfeed.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="../enquiry system/index.php" class="nav-link">Home</a>
        <a href="admDash.php" class="nav-link">Dashboard</a>
        <a href="mngStudent.php" class="nav-link">Manage Students</a>
        <a href="mngStaff.php" class="nav-link">Manage Staff</a>
        <a href="Adfeed.php" class="nav-link active">Feedback</a>
        <a href="../admin/adminlogin.php" class="nav-link">Logout</a>

        <!-- Footer inside Sidebar -->
        <footer>
            <p>&copy; <?php echo date("Y"); ?> StudyConnect. All rights reserved.</p>
        </footer>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <h1 class="text-center">Professor Feedback</h1>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>staff Name</th>
                        <th>Title</th>
                        <th>Feedback</th>
                        <th>Sent Date</th>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr id="feedback-<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['professor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['feedback']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <!-- Reply Button -->
                                    <?php if (empty($row['response'])): ?>
                                        <button class="btn btn-primary btn-sm mt-2 reply-btn" data-id="<?php echo $row['id']; ?>">Reply</button>
                                    <?php else: ?>
                                        <p><strong>Replied:</strong> <?php echo htmlspecialchars($row['response']); ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm mt-2 delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No feedback found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript for handling reply and delete -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            // Reply functionality
            $('.reply-btn').on('click', function() {
                var feedbackId = $(this).data('id');
                var replyForm = `
                    <form method="POST" action="">
                        <textarea name="response" class="form-control mt-2" placeholder="Type your reply here..." required></textarea>
                        <input type="hidden" name="reply_feedback_id" value="${feedbackId}">
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Submit Reply</button>
                    </form>
                `;
                $(this).parent().html(replyForm); // Replace the reply button with the form
            });

            // Delete functionality
            $('.delete-btn').on('click', function() {
                var feedbackId = $(this).data('id');
                if (confirm('Are you sure you want to delete this feedback?')) {
                    $.post('', { delete_feedback_id: feedbackId }, function(response) {
                        $('#feedback-' + feedbackId).remove(); // Remove the feedback from the table
                    });
                }
            });
        });
    </script>
</body>
</html>
