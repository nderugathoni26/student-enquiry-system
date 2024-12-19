<?php
session_start();

/**
 * Check if professor is logged in.
 *
 * @return bool
 */
function isProfessorLoggedIn() {
    return isset($_SESSION['professor_id']);
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
 * Log in the professor.
 *
 * @param int $professorId
 * @param string $professorName
 */
function loginProfessor($professorId, $professorName) {
    $_SESSION['professor_id'] = $professorId;
    $_SESSION['professor_name'] = $professorName;
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}

/**
 * Log out the professor.
 */
function logoutProfessor() {
    session_unset();
    session_destroy();
    redirect('stafflogin.php'); // Redirect to staff login after logout
}

/**
 * Check for session timeout.
 *
 * @param int $timeout Time in seconds. Default is 30 minutes (1800 seconds).
 */
function checkProfessorSessionTimeout($timeout = 1800) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logoutProfessor(); // If the session has timed out, log out the professor
    }
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}

// Always check for session timeout on every page load
checkProfessorSessionTimeout();
?>
