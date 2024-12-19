<?php
session_start();
include '../student/db.php';  // Include your database connection

// Check if the professor is logged in
if (!isset($_SESSION['professor_name'])) {
    header("Location: ../resources/sentresource.php");
    exit();
}

$professor_name = $_SESSION['professor_name'];
$message = "";

// Allowed file extensions and max size (e.g., 5 MB)
$allowed_extensions = array('pdf', 'doc', 'docx', 'ppt', 'pptx');
$max_file_size = 5 * 1024 * 1024; // 5MB

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Handle file upload
    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] == 0) {
        $file_tmp = $_FILES['resource_file']['tmp_name'];
        $file_name = $_FILES['resource_file']['name'];
        $file_size = $_FILES['resource_file']['size'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Set the upload destination path
        $upload_directory = '../resources/uploads/'; // Make sure this directory exists
        $file_destination = $upload_directory . basename($file_name);

        // Validate file extension and size
        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "<div class='alert alert-danger'>Invalid file type. Only PDF, DOC, DOCX, PPT, and PPTX are allowed.</div>";
        } elseif ($file_size > $max_file_size) {
            $message = "<div class='alert alert-danger'>File size exceeds the 5MB limit.</div>";
        } else {
            // Move uploaded file to the destination
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Insert the resource details into the database
                $sql = "INSERT INTO resources (professor_name, category, title, description, file_path) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                // Sanitize inputs before binding
                $stmt->bind_param('sssss', $professor_name, $category, $title, $description, $file_destination);

                if ($stmt->execute()) {
                    // Redirect to reviewsent.php after successful upload
                    header("Location: ../resources/reviewsent.php");
                    exit(); // Ensure no further code is executed after redirect
                } else {
                    $message = "<div class='alert alert-danger'>Error posting the resource. Please try again.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Error uploading the file. Please try again.</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-danger'>Please upload a valid file.</div>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Study Resources</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../css/sentresource.css" rel="stylesheet">
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
                    <a class="nav-link" href="../enquiries/manage.php">Manage Enquiries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active " href="../resources/sentresource.php">Post Study Resources</a>
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
        <div class="card" style=" overflow-y: auto;" >
            <div class="card-header">
                <h2>Post Study Resources</h2>
            </div>
            <div class="card-body" >
                <?php echo $message; ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Category Selection Dropdown -->
                    <div class="form-group">
                        <label for="resource-category">Category:</label>
                        <select id="resource-category" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="notes">Notes/PDF/PowerPoint</option>
                            <option value="exams">Exams</option>
                        </select>
                    </div>

                    <!-- Title Input -->
                    <div class="form-group">
                        <label for="resource-title">Title:</label>
                        <input type="text" id="resource-title" name="title" class="form-control" placeholder="Enter resource title" required>
                    </div>

                    <!-- Description Textarea -->
                    <div class="form-group">
                        <label for="resource-description">Description:</label>
                        <textarea id="resource-description" name="description" class="form-control" rows="4" placeholder="Enter resource description" required></textarea>
                    </div>

                    <!-- File Upload Input -->
                    <div class="form-group">
                        <label for="resource-file">Upload File:</label>
                        <input type="file" id="resource-file" name="resource_file" class="form-control" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Post Resource</button>
                </form>
            </div>
        </div>
    </div>
    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
       </footer>
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       <script src="https://code.jquery.com/jquery-3.5.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

