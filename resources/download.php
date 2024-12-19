<?php
session_start();
include '../student/db.php';  // Include your database connection

// Check if the file ID is set
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
} else {
    echo "No file specified.";
}
?>
