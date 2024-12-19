<?php
include '../student/db.php'; // Database connection

// Fetch staff records from the database
$sql = "SELECT * FROM staff_profiles";
$result = $conn->query($sql);

// Handle Edit Functionality
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name']; // Use 'name' as per the form input
    $email = $_POST['email']; // Use 'email' as per the form input

    // Update query to modify staff records
    $sql = "UPDATE staff_profiles SET username = '$name', email = '$email' WHERE profile_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: mngStaff.php"); // Reload page after update
        exit; // Always use exit after a header redirect
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle Delete Functionality
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Delete query to remove staff record
    $sql = "DELETE FROM staff_profiles WHERE profile_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: mngStaff.php"); // Reload page after deletion
        exit; // Always use exit after a header redirect
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staff</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/adstaff.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="../enquiry system/index.php" class="nav-link">Home</a>
        <a href="admDash.php" class="nav-link">Dashboard</a>
        <a href="mngStudent.php" class="nav-link">Manage Students</a>
        <a href="mngStaff.php" class="nav-link active">Manage Staff</a>
        <a href="Adfeed.php" class="nav-link">Feedback</a>
        <a href="../admin/adminlogin.php" class="nav-link">Logout</a>

        <!-- Footer inside Sidebar -->
        <footer>
            <p>&copy; 2024 StudyConnect. All rights reserved.</p>
        </footer>
    </div>

    <div class="container mt-5">
        <h1 class="text-center" style="color: white;">Manage Staff</h1>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['profile_id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['profile_id']; ?>">Edit</button>
                                    <a href="?delete_id=<?php echo $row['profile_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['profile_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Staff Member</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['profile_id']; ?>">
                                                <div class="form-group">
                                                    <label for="name">Name:</label>
                                                    <input type="text" class="form-control" name="name" value="<?php echo $row['username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No staff members found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- Bootstrap JS and dependencies (for modal functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
