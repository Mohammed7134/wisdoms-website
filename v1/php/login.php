<?php
require_once '../../includes/DbAuth.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $db = new DbAuth();
        $result = $db->userLogin($username, $password);
        if (isset($result['id'])) {
            $response['error'] = false;
            $response['user'] = $result;
            session_start();
            setcookie("status-log", "true", 31536000, "/");
            $_SESSION['logged in'] = "true";
            $_SESSION['admin'] = $response['user']['admin'] == 'true' ? true : false;
        } else if ($result == ACCOUNT_INACTIVE) {
            $response['error'] = true;
            $response['message'] = 'Your account is not yet accepted by admin';
        } else if ($result == INVALID_CREDENTIALS) {
            $response['error'] = true;
            $response['message'] = 'Invalid username or password';
        } else if ($result == OBJECT_NOT_EXIST) {
            $response['error'] = true;
            $response['message'] = 'Invalid username or password';
        } else {
            $response['error'] = true;
            $response['message'] = $result;
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Parameters are missing';
    }
} else {
    $response['error'] = true;
    $response['message'] = "Request not allowed";
}
if (isset($_GET['api'])) {
    echo json_encode($response);
} else {
    header("Location: ../../home");
    exit;
}
