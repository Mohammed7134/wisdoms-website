<?php
session_start();
$response = array();
require_once '../../../etc/includes/DbOperations.php';
$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
if (!isset($_SESSION['logged in'])) {
    if ($cookie) {
        $db = new DbOperations();
        list($user, $token, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, "MOHAMMED_ASRAA"), $mac)) {
            $response['error'] = true;
            $response['message'] = 'No matching cookie';
            echo json_encode($response);
            return;
        }
        $usertoken = $db->fetchTokenByUserName($user);
        if (hash_equals($usertoken, $token)) {
            $_SESSION['logged in'] = "true";
            $response['error'] = false;
            $response['message'] = 'LOGIN';
        } else {
            $response['error'] = true;
            $response['message'] = 'LOGOUT';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'No cookie stored';
    }
} else {
    $logState = $_SESSION['logged in'];
    if ($logState == "true") {
        $response['error'] = false;
        $response['message'] = 'LOGIN';
    } else {
        $response['error'] = true;
        $response['message'] = 'LOGOUT';
    }
}
echo json_encode($response);
