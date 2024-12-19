<?php
// Include your database connection file
include('../student/db.php'); // Adjust the path as needed

// Query to get the total number of staff
$staffQuery = "SELECT COUNT(*) AS totalStaff FROM professors";
$staffResult = mysqli_query($conn, $staffQuery);
$staffData = mysqli_fetch_assoc($staffResult);
$totalStaff = $staffData['totalStaff'];

// Query to get the total number of students
$studentsQuery = "SELECT COUNT(*) AS totalStudents FROM students";
$studentsResult = mysqli_query($conn, $studentsQuery);
$studentsData = mysqli_fetch_assoc($studentsResult);
$totalStudents = $studentsData['totalStudents'];

// Query to get the total number of enquiries
$enquiriesQuery = "SELECT COUNT(*) AS totalEnquiries FROM enquiries";
$enquiriesResult = mysqli_query($conn, $enquiriesQuery);
$enquiriesData = mysqli_fetch_assoc($enquiriesResult);
$totalEnquiries = $enquiriesData['totalEnquiries'];

// Query to get the total number of resources posted
$resourcesQuery = "SELECT COUNT(*) AS totalResources FROM resources";
$resourcesResult = mysqli_query($conn, $resourcesQuery);
$resourcesData = mysqli_fetch_assoc($resourcesResult);
$totalResources = $resourcesData['totalResources'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../css/adDash.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="../enquiry system/index.php">Home</a>
        <a href="adminpanel.php" class="active">Dashboard</a>
        <a href="mngStudent.php">Manage Students</a>
        <a href="mngStaff.php">Manage Staff</a>
        <a href="Adfeed.php">Feedback</a>
        <a href="../admin/adminlogin.php">Logout</a>
        
        <!-- Footer moved inside the sidebar -->
        <footer>
            <p>&copy; 2024 StudyConnect. All rights reserved.</p>
        </footer>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="text-center">Admin Dashboard Overview</h1>
        <div class="grid-container">
            <!-- Display total staff -->
            <div class="card">
                <h3 style="color: navy;">Total Staff</h3>
                <p><?php echo $totalStaff; ?></p>
            </div>

            <!-- Display total students -->
            <div class="card">
                <h3 style="color: navy;">Total Students</h3>
                <p><?php echo $totalStudents; ?></p>
            </div>

            <!-- Display total enquiries -->
            <div class="card">
                <h3 style="color: navy;">Total Enquiries</h3>
                <p><?php echo $totalEnquiries; ?></p>
            </div>

            <!-- Display total resources posted -->
            <div class="card">
                <h3 style="color: navy;">Total Resources Posted</h3>
                <p><?php echo $totalResources; ?></p>
            </div>

            <!-- Bar Graph Section -->
            <div class="bar-container">
                <h3 style="color: navy;">Summary Bar Graph</h3>
                <canvas id="summaryBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        // Bar Chart
        const barCtx = document.getElementById('summaryBarChart').getContext('2d');
        const summaryBarChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Total Staff', 'Total Students', 'Total Enquiries', 'Total Resources'],
                datasets: [{
                    label: 'Count',
                    data: [
                        <?php echo $totalStaff; ?>,
                        <?php echo $totalStudents; ?>,
                        <?php echo $totalEnquiries; ?>,
                        <?php echo $totalResources; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

