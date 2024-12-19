<?php
session_start();

/**
 * Check if student is logged in.
 *
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['student_id']);
}

/**
 * Redirect to a specific URL.
 *
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Log in the student.
 *
 * @param int $studentId
 * @param string $username
 */
function login($studentId, $username) {
    $_SESSION['student_id'] = $studentId;
    $_SESSION['username'] = $username;
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}

/**
 * Log out the student.
 */
function logout() {
    session_unset();
    session_destroy();
    redirect('studentlogin.php');
}

/**
 * Check for session timeout.
 *
 * @param int $timeout Time in seconds. Default is 30 minutes (1800 seconds).
 */
function checkSessionTimeout($timeout = 1800) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logout(); // If the session has timed out, log out the user
    }
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}

// Always check for session timeout on every page load
checkSessionTimeout(); 