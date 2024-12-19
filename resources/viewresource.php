<?php
session_start();
include '../student/db.php';  // Include your database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the file ID is set for download
if (isset($_POST['file_id'])) {
    $file_id = $_POST['file_id'];

    // Prepare the SQL statement to get the file path
    $stmt = $conn->prepare("SELECT file_path FROM resources WHERE id = ?");
    $stmt->bind_param('i', $file_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Set the file path (assuming files are stored in the 'uploads' directory)
        $file_path = '../resources/uploads/' . basename($row['file_path']); // Construct the full path

        // Check if the file exists
        if (file_exists($file_path)) {
            // Set headers to trigger download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            flush(); // Flush system output buffer
            readfile($file_path); // Read the file
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "File not found.";
    }
}

// Get search input
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch resources based on the search term or fetch all if search term is empty
$sql = "SELECT id, title, description, category, created_at, file_path FROM resources";

if (!empty($search_term)) {
    $sql .= " WHERE title LIKE ? OR category LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Failed to prepare statement: " . $conn->error);
}

// Bind parameters if search term is present
if (!empty($search_term)) {
    $search_param = '%' . $search_term . '%';
    $stmt->bind_param('ss', $search_param, $search_param);
}

// Execute the query
if ($stmt->execute() === false) {
    die("Failed to execute query: " . $stmt->error);
}

// Get the result
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
    <title>View Sent Resources</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
    background-image: url('../images/staffpanel.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}


::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar but allow scrolling */
.container {
    overflow: scroll; /* or overflow-y: scroll for vertical scrolling */
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.navbar {
    background-color: rgba(161, 98, 98, 0.9); /* Darker background for better contrast */
    padding: 20px 40px; /* Add padding for spacing */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}


.navbar-brand {
    font-size: 1.5rem; /* Larger brand text */
    font-weight: bold; /* Bold brand text */
    color: white; /* Brand text color */
}

.navbar-nav .nav-link {
    color: white; /* Default link color */
    padding: 10px 15px; /* Spacing around links */
    font-weight: 500; /* Semi-bold links */
    transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Light background on hover */
    color: #fff; /* Ensure text stays white on hover */
}

.navbar-nav .nav-link.active {
    background-color: rgba(255, 255, 255, 0.3); /* Active link background */
    color: white; /* Active link text color */
    border-radius: 4px; /* Rounded corners for active link */
}

#Dash-board {
    
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
    
}


.card {
    border-radius: 8px; /* Rounded corners for the cards */
 transition: transform 0.2s
}

/* Scale up the card on hover */


/* Card header styles */
.card-header {
    font-size: 1.25rem; /* Larger font size for the card header */
    font-weight: bold; /* Make the header bold */
    text-align: center;
    
}

/* Card body text styles */
.card-body {
    font-size: 1rem; /* Default font size for card body */
    
}

/* Button styles */
.text-white {
    color: white; /* Ensure text is white on colored backgrounds */
}

/* Specific color adjustments for cards */
.bg-primary {
    background-color: rgb(161, 98, 98) !important; /* Primary color */
}

.bg-success {
    background-color: rgb(161, 98, 98) !important; /* Success color */
}

.bg-warning {
    background-color:rgb(161, 98, 98) !important; /* Warning color */
}

.bg-info {
    background-color: rgb(161, 98, 98) !important/* Info color */
}

.bg-secondary {
    background-color: rgb(161, 98, 98) !important/* Secondary color */
}


.card-header{
background-color: rgb(161, 98, 98);
padding: 20px;
}
.card-title{
color: black;

}
.card-text{
color: black;
}

.card-body{
background-color: rgba(255, 255, 255, 0.806);
}

.col-lg-4 col-md-6 mb-4{
border-radius: 10px;
}



.card {
    
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.187);
    margin-top: 95px;
    margin-bottom: 20px;
}

h1{
    color: white;
    font-size: 2.5rem;
    font-weight: bolder;
    display: block;
    background-color:rgb(161, 98, 98) ;
    width: 40%;
    padding: 10px;
    border-radius: 10px;
    margin: 0 auto;
    justify-content: center;
    text-wrap: center;
    text-align: center;
}


.card-header {
    background-color: rgba(161, 98, 98, 0.769);
    color: white;
}

.d-none {
    display: none;
}

footer {
    background-color: rgb(161, 98, 98);
    text-align: center;
    font-size: large;
    padding: 10px;
    position: fixed;
    bottom: 0;
    width: 100%;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .navbar {
        padding: 10px; /* Reduce padding on mobile */
    }

    .navbar-nav .nav-link {
        font-size: 0.9rem; /* Slightly smaller text on mobile */
    }
}
    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="../enquiry system/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../student/dashstudent.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../enquiries/post.php">Post an Enquiry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../lecturers/lecturers.php">View Lectures</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../enquiries/pending.php">My Enquiries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../resources/viewresource.php">View Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../messages/send.php">Message Peers</a>
                 </li>
                <li class="nav-item">
                    <a class="nav-link" href="../messages/send.php">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../student/studentlogin.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="container mt-5">
    <div class="card" style="margin-top: 10px;">
        <h2 class="card-header">Sent Resources</h2>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="search-bar d-flex justify-content-center my-4">
                <form method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search by title or category" value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>

            <!-- Resource Table -->
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover">
                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Resource Title</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Date Sent</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr id="resource-<?php echo $row['id']; ?>">
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="file_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Download</button>
                                        </form>
                                    </td>
                                    <td>
                                       
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No resources found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="text-center text-white">
    <p>&copy; 2024 StudyConnect. All rights reserved.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
