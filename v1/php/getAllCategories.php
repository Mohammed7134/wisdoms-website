<?php
if (isset($_GET['api'])) {
    session_start();
}
$response = array();
require_once '../../../etc/includes/DbOperations.php';
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
$db = new DbOperations();
$result = $db->getAllCategories();
if ($result !== false) {
    $response['error'] = false;
    $response['categories'] = $result;
} else {
    $response['error'] = true;
    $response['message'] = "Something went wrong";
}
// } else {
//     $response['error'] = true;
//     $response['message'] = "Request not allowed";
// }
if (isset($_GET['api'])) {
    echo json_encode($response);
} else {
    if ($response['error'] === false) {
        return json_encode($response);
    } else {
        echo ("problem 1");
    }
}
