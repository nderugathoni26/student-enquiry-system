<?php
// Database connection file
include '../student/db.php'; // Adjust the path as necessary

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../student/studentlogin.php"); // Redirect to login page if not logged in
    exit();
}

$currentUserId = $_SESSION['user_id']; // Get the current logged-in user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
    $messageIdToDelete = $_POST['delete_message_id'];
    $sqlDelete = "DELETE FROM messages WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $messageIdToDelete);
    if ($stmtDelete->execute()) {
        echo "<script>alert('Message deleted successfully!');</script>";
        echo "<script>window.location.href = '../messages/send.php';</script>";
    } else {
        echo "Error deleting the message: " . $stmtDelete->error;
    }
    $stmtDelete->close();
}

// Handle form submission for sending a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['message'])) {
    $recipient_id = isset($_POST['recipient_id']) ? $_POST['recipient_id'] : null;
    $title = $_POST['title'];
    $message = $_POST['message'];
    
    if ($recipient_id && $title && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, title, message, sent_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $currentUserId, $recipient_id, $title, $message);
        if ($stmt->execute()) {
            echo "<script>alert('Message sent successfully!');</script>";
        } else {
            echo "<script>alert('Error sending message: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Fetch list of students excluding the currently logged-in user
$studentsResult = $conn->query("SELECT user_id, username FROM students WHERE user_id != $currentUserId");

// Fetch messages where the current user is the sender and the message has been replied to
$sql = "SELECT m.*, s.username AS recipient_name 
        FROM messages m
        JOIN students s ON m.recipient_id = s.user_id
        WHERE m.sender_id = ? AND m.is_replied = 1
        ORDER BY m.sent_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel - Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/sendmssg.css" rel="stylesheet">
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
                <li class="nav-item"><a class="nav-link" href="../lecturers/lecturers.php">View</a></li>
                <li class="nav-item"><a class="nav-link" href="../enquiries/pending.php">My Enquiries</a></li>
                <li class="nav-item"><a class="nav-link" href="../resources/viewresource.php">View Resources</a></li>
                <li class="nav-item"><a class="nav-link active" href="../messages/send.php">Message Peers</a></li>
                <li class="nav-item"><a class="nav-link" href="../messages/replymssg.php">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="../student/studentlogin.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <section id="send-feedback">
                <div class="card">
                    <div class="card-header">
                        <h2>Send Message:</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="recipient_id" class="form-label">Recipient Username</label>
                                <select name="recipient_id" class="form-control" required>
                                    <option value="" disabled selected>Select Recipient</option>
                                    <?php while ($student = $studentsResult->fetch_assoc()): ?>
                                        <option value="<?= $student['user_id'] ?>"><?= htmlspecialchars($student['username']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section id="view-feedback">
                <div class="card">
                    <div class="card-header">
                        <h2>Replied Messages:</h2>
                    </div>
                    <div class="card-body">
                        <ul>
                         <?php while ($row = $result->fetch_assoc()): ?>
                            <li>
                                <b><strong>From: </strong><?= htmlspecialchars($row['recipient_name']) ?></b><br>
                                <p><strong>Title: </strong> <?= htmlspecialchars($row['title']) ?></p>
                                <p><strong>Message: </strong> <?= htmlspecialchars($row['message']) ?></p>
                                <p><strong>Reply: </strong> <?= htmlspecialchars($row['replied_message']); ?></p>
                                <small>Replied at: <?= $row['sent_at'] ?></small>
                                 <form method="post" style="display: inline;">
                                    <input type="hidden" name="delete_message_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?');">Delete</button>
                                </form>
                            </li>
                            <hr>
                        <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<footer class="text-center text-white">
    <p>&copy; 2024 StudyConnect. All rights reserved.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
