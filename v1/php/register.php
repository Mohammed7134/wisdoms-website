<?php
require_once '../../../etc/includes/DbAuth.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $db = new DbAuth();
    $result = $db->createUser($username, $password);
    if ($result == OBJECT_CREATED) {
        $response['error'] = false;
        $response['message'] = 'Please check your inbox after 5 minutes';
    } elseif ($result == OBJECT_ALREADY_EXIST) {
        $response['error'] = true;
        $response['message'] = 'User already exist';
    } elseif ($result == OBJECT_NOT_CREATED) {
        $response['error'] = true;
        $response['message'] = 'Some error occurred';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
echo json_encode($response);
