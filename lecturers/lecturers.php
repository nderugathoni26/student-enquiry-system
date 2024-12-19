<?php
// Connect to the database
include '../student/db.php'; // Ensure this points to your actual DB connection file

// Function to fetch staff by category
function fetchStaffByCategory($category, $conn) {
    $query = "
        SELECT sp.professor_name 
        FROM staff_profiles AS sp 
        JOIN professors AS p ON sp.profile_id = p.profile_id 
        WHERE sp.category = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $staff = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
    }
    return $staff;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lectures</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
            background-color: rgb(161, 98, 98); 
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

        #Dash-board {
            background-image: url('../images/studentpanel.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .card {
            margin-top: 95px;
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: rgba(128, 128, 128, 0.568);
        }

        .card-header {
            background-color: rgba(161, 98, 98, 0.733);
            padding: 20px;
            font-size: 1.25rem; 
            font-weight: bold; 
            text-align: center;
        }

        .card-body {
            background-color: rgba(255, 255, 255, 0.806);
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

        @media (max-width: 768px) {
            .navbar {
                padding: 10px; 
            }

            .navbar-nav .nav-link {
                font-size: 0.9rem; 
            }

            .card {
                margin: 10px; 
            }

            h1 {
                font-size: 2rem; 
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
                    <a class="nav-link active" href="../lecturers/lecturers.php">View Staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../enquiries/pending.php">My Enquiries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../resources/viewresource.php">View Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../messages/send.php">Message Peers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../messages/replymssg.php">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../student/studentlogin.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="container mt-5">
    <!-- View Staff Section -->
    <section id="view-staff">
        <div class="card">
            <div class="card-header">
                <h2>View Staffs</h2>
            </div>
            <div class="card-body">
                <h4>Departments</h4>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" onclick="showStaff('Academic')">Academic</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="showStaff('Exams')">Exams</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="showStaff('Student Welfare')">Student Welfare</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="showStaff('General')">General</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Staff -->
    <section id="Academic" class="d-none">
        <div class="card mt-3">
            <div class="card-header">
                <h2>Academic</h2>
            </div>
            <div class="card-body">
                <ul>
                    <?php
                    $academicStaff = fetchStaffByCategory('Academic', $conn);
                    if (empty($academicStaff)) {
                        echo "<li>No available staff found under Academic.</li>";
                    } else {
                        foreach ($academicStaff as $staff) {
                            echo "<li>{$staff['professor_name']}</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="backToCategories()">Back to Categories</button>
        </div>
    </section>

    <!-- Exams Staff -->
    <section id="Exams" class="d-none">
        <div class="card mt-3">
            <div class="card-header">
                <h2>Exams</h2>
            </div>
            <div class="card-body">
                <ul>
                    <?php
                    $examsStaff = fetchStaffByCategory('Exams', $conn);
                    if (empty($examsStaff)) {
                        echo "<li>No available staff found under Exams.</li>";
                    } else {
                        foreach ($examsStaff as $staff) {
                            echo "<li>{$staff['professor_name']}</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="backToCategories()">Back to Categories</button>
        </div>
    </section>

    <!-- Student Welfare Staff -->
    <section id="Student Welfare" class="d-none">
        <div class="card mt-3">
            <div class="card-header">
                <h2>Student Welfare</h2>
            </div>
            <div class="card-body">
                <ul>
                    <?php
                    $welfareStaff = fetchStaffByCategory('Student Welfare', $conn);
                    if (empty($welfareStaff)) {
                        echo "<li>No available staff found under Student Welfare.</li>";
                    } else {
                        foreach ($welfareStaff as $staff) {
                            echo "<li>{$staff['professor_name']}</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="backToCategories()">Back to Categories</button>
        </div>
    </section>

    <!-- General Staff -->
    <section id="General" class="d-none">
        <div class="card mt-3">
            <div class="card-header">
                <h2>General</h2>
            </div>
            <div class="card-body">
                <ul>
                    <?php
                    $generalStaff = fetchStaffByCategory('General', $conn);
                    if (empty($generalStaff)) {
                        echo "<li>No available staff found under General.</li>";
                    } else {
                        foreach ($generalStaff as $staff) {
                            echo "<li>{$staff['professor_name']}</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <button class="btn btn-primary" onclick="backToCategories()">Back to Categories</button>
        </div>
    </section>
</div>

<footer style="color: #fff;">
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>
<script src="../js/staff.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
