<?php
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../../../etc/includes/DbOperations.php';
    $db = new DbOperations();
    $result = $db->getRandomWisdom();
    if ($result !== false) {
        $response['status'] = 200;
        $response['wisdom'] = $result;
    } else {
        $response['status'] = 400;
        $response['message'] = "Something went wrong";
    }
} else {
    $response['status'] = 400;
    $response['message'] = "Invalid Request";
}
echo json_encode($response);
