<?php
session_start();
$response = array();
if (isset($_SESSION['logged in'])) {
    $logState = $_SESSION['logged in'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['logged in'] = "false";
        session_unset();
        session_destroy();
        setcookie('rememberme', "", time() - 1, "/");
        $response['error'] = false;
        $response['message'] = 'logged out';
    } else {
        $response['error'] = true;
        $response['message'] = 'Request is not allowed';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Not Logged In';
}
header('Location: ../../');
exit;
