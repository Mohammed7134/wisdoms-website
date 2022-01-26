<?php
if (isset($_GET['api'])) {
    session_start();
}
$response = array();
// echo $_SERVER['DOCUMENT_ROOT'] . '/../etc/includes/DbOperations.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../etc/includes/DbOperations.php';
$db = new DbOperations();
$result = $db->getAllWisdoms();
if ($result !== false) {
    $response['error'] = false;
    $response['wisdoms'] = [];
    for ($i = 0; $i < 15; $i++) {
        array_push($response['wisdoms'], $result[$i]);
    }
} else {
    $response['error'] = true;
    $response['message'] = "Something went wrong";
}

if (isset($_GET['api'])) {
    echo json_encode($response);
} else {
    return json_encode($response);
}
