<?php
session_start();
$response = array();
require_once '../../includes/DbOperations.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new DbOperations();
    $result = $db->loadBoOmarDb3();
    if ($result !== false) {
        $response['error'] = false;
        $response['wisdoms'] = $result;
    } else {
        $response['error'] = true;
        $response['message'] = "Something went wrong";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Request not allowed";
}
echo json_encode($response);
