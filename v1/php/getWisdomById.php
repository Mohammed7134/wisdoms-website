<?php
if (isset($_GET['api'])) {
    session_start();
}
$response = array();
require_once '../../includes/DbOperations.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new DbOperations();
    $result = $db->retrieveWisdomById($_GET['id']);
    if ($result !== false) {
        $response['error'] = false;
        $response['wisdom'] = $result;
    } else {
        $response['error'] = true;
        $response['message'] = "Something went wrong";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Request not allowed";
}
if (isset($_GET['api'])) {
    echo json_encode($response);
} else {
    return json_encode($response);
}
