<?php
session_start();
include '../student/db.php'; // Include your database connection

// Check if the professor is logged in
if (!isset($_SESSION['professor_name'])) {
    header("Location: ../resources/reviewsent.php");
    exit();
}

// Get the logged-in professor's name
$professor_name = $_SESSION['professor_name'];

// Fetch resources posted by the logged-in professor
$sql = "SELECT id, title, created_at FROM resources WHERE professor_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $professor_name);
$stmt->execute();
$result = $stmt->get_result();

$resources = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row; // Store the fetched resources in an array
    }
}

// Check if a form has been submitted for deleting a resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_resource'])) {
    $resourceId = intval($_POST['resource_id']);

    // Prepare and execute the SQL DELETE query
    $deleteQuery = "DELETE FROM resources WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $resourceId);

    if ($stmt->execute()) {
        // If the deletion was successful, you can add a success message (optional)
        echo '<div class="alert alert-success">Resource deleted successfully!</div>';
    } else {
        // If there was an error deleting, show an error message
        echo '<div class="alert alert-danger">Error deleting resource. Please try again later.</div>';
    }

    // Close the prepared statement
    $stmt->close();

    // Refresh the page after deletion
    header("Location: reviewsent.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Enquiry Management</title>
    <!-- Bootstrap CSS CDN -->  
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="" rel="stylesheet">
    <style>
        body {
            background-image: url('../images/studentpanel.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        ::-webkit-scrollbar {
            display: none;
        }
        .container {
            overflow: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .navbar {
            background-color: rgba(161, 98, 98, 0.9);
            padding: 20px 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
        .navbar-nav .nav-link {
            color: white;
            padding: 10px 15px;
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 4px;
        }
        .card {
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
        }
        .card-body {
            font-size: 1rem;
        }
        .text-white {
            color: white;
        }
        .bg-primary, .bg-success, .bg-warning, .bg-info, .bg-secondary {
            background-color: rgb(161, 98, 98) !important;
        }
        .card-header {
            background-color: rgb(161, 98, 98);
            padding: 20px;
        }
        .card-title, .card-text {
            color: black;
        }
        .card-body {
            background-color: rgba(255, 255, 255, 0.806);
        }
        .card {
            border: none;
            max-width: 90% ;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.187);
            margin-top: 95px;
            margin-left: 20%;
            margin-right: 20%;
            margin-bottom: 20px;
        }
        h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: bolder;
            display: block;
            background-color: rgb(161, 98, 98);
            width: 40%;
            padding: 10px;
            border-radius: 10px;
            margin: 0 auto;
            justify-content: center;
            text-align: center;
        }
        footer {
            background-color: rgb(161, 98, 98);
            text-align: center;
            font-size: large;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
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
                    <a class="nav-link" href="../enquiries/manage.php">Manage Enquiries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../resources/sentresource.php">Post Study Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../resources/reviewsent.php">View Sent Resources</a>
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
    
    <!-- View sent resources -->
   <div class="conatainer mt-5">
   <section id="sent-resources" class="mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="header-title">Sent Resources</h2>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-dark" >
                        <tr>
                            <th>Resource Title</th>
                            <th>Date Sent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($resources)): ?>
                            <?php foreach ($resources as $resource): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                    <td><?php echo htmlspecialchars($resource['created_at']); ?></td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="resource_id" value="<?php echo $resource['id']; ?>">
                                            <button type="submit" name="delete_resource" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No resources found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
   </div>

    <footer class="text-center text-white">
        <p>&copy; 2024 StudyConnect. All rights reserved.</p>
    </footer>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
