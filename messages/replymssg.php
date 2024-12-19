<?php
session_start();  // Start the session at the beginning of the page

// Include your database connection
include '../student/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the student is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: ../student/studentlogin.php");
    exit();
}

$loggedInStudentId = $_SESSION['user_id']; // Ensure user_id is stored in session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'], $_POST['message_id'])) {
    $replyText = $_POST['reply_text'];
    $messageId = $_POST['message_id'];

    // Retrieve the original message to get the sender and recipient IDs
    $sqlMessage = "SELECT sender_id, recipient_id FROM messages WHERE id = ?";
    $stmtMessage = $conn->prepare($sqlMessage);
    $stmtMessage->bind_param("i", $messageId);
    $stmtMessage->execute();
    $messageResult = $stmtMessage->get_result();
    
    // Check if the message was found
    if ($messageResult && $messageResult->num_rows > 0) {
        $messageRow = $messageResult->fetch_assoc();
        $recipientId = $messageRow['sender_id']; // The original sender will be the recipient of the reply
        
        // Update the original message with the reply
        $sqlUpdate = "UPDATE messages SET replied_message = ?, replied_at = NOW(), is_replied = 1 WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        
        if ($stmtUpdate) {
            $stmtUpdate->bind_param("si", $replyText, $messageId);
            
            if ($stmtUpdate->execute()) {
                echo "<script>alert('Reply sent successfully!');</script>";
            } else {
                echo "Error updating the reply: " . $stmtUpdate->error;
            }
            
            $stmtUpdate->close();
        } else {
            echo "Error preparing update statement: " . $conn->error;
        }
    } else {
        // Message not found
        echo "Error: Original message not found.";
    }
    
    // Clean up
    $stmtMessage->close();
}
    

    // Check if it's a delete request
    if (isset($_POST['delete_message_id'])) {
        $deleteMessageId = $_POST['delete_message_id'];

        // Delete the message from the database
        $sqlDelete = "DELETE FROM messages WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        if ($stmtDelete) {
            $stmtDelete->bind_param("i", $deleteMessageId);
            if ($stmtDelete->execute()) {
                echo "<script>alert('Message deleted successfully!');</script>";
            } else {
                echo "Error deleting message: " . $stmtDelete->error;
            }
            $stmtDelete->close();
        } else {
            echo "Error preparing delete statement: " . $conn->error;
        }
    }


// Fetch messages meant for the logged-in user
$sql = "SELECT m.id, s.username AS sender_name, m.title, m.message, m.sent_at, m.is_replied, m.replied_message 
        FROM messages m 
        JOIN students s ON m.sender_id = s.user_id
        WHERE m.recipient_id = ?";  // Use recipient_id from the messages table

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("i", $loggedInStudentId);  // Bind the recipient ID (user_id)
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    die("Failed to get result: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/replymssg.css">
    <script src="../js/rep.js"> </script>
</head>
<body>

<div class="navbar-container">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="../student/studentpanel.php">Student Panel</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../enquiry system/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../student/dashstudent.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../enquiries/post.php">Post an Enquiry</a></li>
                <li class="nav-item"><a class="nav-link" href="../lecturers/lecturers.php">View staff</a></li>
                <li class="nav-item"><a class="nav-link" href="../enquiries/pending.php">My Enquiries</a></li>
                <li class="nav-item"><a class="nav-link" href="../resources/viewresource.php">View Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="../messages/send.php">Message Peers</a></li>
                <li class="nav-item"><a class="nav-link active" href="../messages/replymssg.php">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="../student/studentlogin.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Messages</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover">
                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                    <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                                    <td><?php echo isset($row['title']) && !is_null($row['title']) ? htmlspecialchars($row['title']) : 'No Title'; ?></td>
                                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                                    <td><?php echo $row['is_replied'] && !empty($row['replied_message']) ? htmlspecialchars($row['replied_message']) : 'No reply yet'; ?></td>
                                    <td><?php echo htmlspecialchars($row['sent_at']); ?></td>
                                    <td><?php echo $row['is_replied'] ? 'Replied' : 'Pending'; ?></td>
                                    
                                    <td>
                                        <?php if (!$row['is_replied']): ?>
                                            <!-- Button to toggle reply input -->
                                            <button class="btn btn-primary btn-sm" onclick="toggleReplyInput(<?php echo $row['id']; ?>)">Reply</button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>Replied</button>
                                        <?php endif; ?>

                                        <!-- Delete Button -->
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="delete_message_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?');">Delete</button>
                                        </form>

                                        <!-- Reply Form -->
                                        <div id="reply-<?php echo $row['id']; ?>" style="display:none; margin-top: 10px;">
                                            <form method="post">
                                                <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                                <input type="text" name="reply_text" placeholder="Reply" required>
                                                <button type="submit" class="btn btn-success btn-sm">Send</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
<script>
// Function to toggle the visibility of the reply input
function toggleReplyInput(id) {
    var replyDiv = document.getElementById('reply-' + id);
    if (replyDiv.style.display === 'none' || replyDiv.style.display === '') {
        replyDiv.style.display = 'block';
    } else {
        replyDiv.style.display = 'none';
    }
}
</script>
<?php endwhile; ?>
</tbody>


                </table>
            </div>
        </div>
    </div>
</div>
<footer class="text-center text-white">
    <p>&copy; 2024 StudyConnect. All rights reserved.</p>
</footer>
</body>
</html>
