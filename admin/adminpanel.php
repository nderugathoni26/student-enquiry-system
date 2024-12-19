
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Enquiry Platform</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link href="../css/adpanel.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="../enquiry system/index.php" class="nav-link " data-target="#">Home</a>
        <a href="#dashboard" class="nav-link active" data-target="dashboard">Dashboard</a>
        <a href="mngStudent.php" class="nav-link" data-target="manage-students">Manage Students</a>
        <a href="#view-staff" class="nav-link" data-target="view-staff">View Staff</a>
        <a href="Adfeed.php" class="nav-link" data-target="feedback">Feedback</a>
        <a href="#settings" class="nav-link" data-target="settings">Settings</a>
        <a href="../admin/adminlogin.php" class="nav-link" data-target="logout">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section-card active">
            <h2>Dashboard</h2>
            
            <!-- Overview Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Students</h5>
                            <p class="card-text display-4">150</p>
                            <p class="small text-muted">Active Students: 140</p>
                            <p class="small text-muted">Inactive Students: 10</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Staff</h5>
                            <p class="card-text display-4">25</p>
                            <p class="small text-muted">Full-time: 20</p>
                            <p class="small text-muted">Part-time: 5</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Enquiries</h5>
                            <p class="card-text display-4">45</p>
                            <p class="small text-muted">Open Enquiries: 15</p>
                            <p class="small text-muted">Closed Enquiries: 30</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Enquiries Responded</h5>
                            <p class="card-text display-4">30</p>
                            <p class="small text-muted">Response Rate: 85%</p>
                            <p class="small text-muted">Avg. Response Time: 24 hrs</p>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- System Insights and Performance -->
            <div class="row">
                <!-- Enquiries Breakdown -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Enquiries Breakdown</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Technical Issues
                                    <span class="badge badge-primary badge-pill">10</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Course Registration
                                    <span class="badge badge-primary badge-pill">12</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Grading Issues
                                    <span class="badge badge-primary badge-pill">8</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Others
                                    <span class="badge badge-primary badge-pill">15</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                <!-- System Performance -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">System Performance</h5>
                            <p><strong>Server Uptime:</strong> 99.9%</p>
                            <p><strong>Average Response Time:</strong> 24 hours</p>
                            <p><strong>User Satisfaction:</strong> 85%</p>
        
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">User Satisfaction</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 99%;" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100">Server Uptime</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 24%;" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100">Average Response Time (hrs)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Additional Insights -->
            <div class="row mt-4">
                <!-- Most Active Students -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Active Students</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    John Doe
                                    <span class="badge badge-success badge-pill">20 Enquiries</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Jane Smith
                                    <span class="badge badge-success badge-pill">18 Enquiries</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Mark Johnson
                                    <span class="badge badge-success badge-pill">15 Enquiries</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                <!-- Most Responsive Staff -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Responsive Staff</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Professor Emily Clark
                                    <span class="badge badge-info badge-pill">15 Responses</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Dr. Andrew Brown
                                    <span class="badge badge-info badge-pill">12 Responses</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Professor Sarah Lee
                                    <span class="badge badge-info badge-pill">10 Responses</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- System Impact Analysis -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">System Impact Analysis</h5>
                            <p>Analyze the system's impact based on various metrics:</p>
                            <ul>
                                <li><strong>Response Rate:</strong> 85%</li>
                                <li><strong>Feedback Analysis:</strong> Positive feedback increased by 10% this month.</li>
                                <li><strong>Areas for Improvement:</strong> Reduce response time for complex enquiries.</li>
                            </ul>
                            <p class="text-muted">Overall, the system performance has been stable with increasing user satisfaction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Manage Students Section -->
        <div id="manage-students" class="section-card">
            <h2>Manage Students</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juliet Anchieng</td>
                        <td>john@example.com</td>
                        <td>(555) 123-4567</td>
                        <td>
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- View Staff Section -->
        <div id="view-staff" class="section-card">
            <h2>View Staff</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Professor Jonny Daniels</td>
                        <td>john.doe@example.com</td>
                        <td>(555) 111-2222</td>
                        <td>Room 101</td>
                        <td>
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Feedback Section -->
        <div id="feedback" class="section-card">
            <h2>Feedback and Responses</h2>
            <form>
                <div class="form-group">
                    <label for="feedback-id">Feedback ID:</label>
                    <input type="text" class="form-control" id="feedback-id" placeholder="Enter feedback ID">
                </div>
                <div class="form-group">
                    <label for="response-message">Response:</label>
                    <textarea class="form-control" id="response-message" rows="4" placeholder="Write your response here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Response</button>
            </form>
        </div>

        <!-- Settings Section -->
        <div id="settings" class="section-card">
            <h2>Settings</h2>
            <hr>
            <p>Update your account settings here.</p>
            <button class="btn btn-primary">Change Password</button>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript to toggle section visibility
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                // Hide all sections
                document.querySelectorAll('.section-card').forEach(section => {
                    section.classList.remove('active');
                });

                // Show the clicked section
                const target = this.getAttribute('data-target');
                document.getElementById(target).classList.add('active');

                // Highlight the active link
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>

    
</body>
</html>
