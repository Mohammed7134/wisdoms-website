<?php
if (isset($_GET['api'])) {
    session_start();
}
$response = array();
require_once '../../../etc/includes/DbOperations.php';
$db = new DbOperations();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = $db->retrieveWisdomById($_GET['id']);
    if ($result !== false) {
        $response['error'] = false;
        $response['wisdom'] = $result;
    } else {
        $response['error'] = true;
        $response['message'] = "Something went wrong";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); //convert JSON into array
    $wisdoms = [];
    for ($i = 0; $i < count($input['wisdomsIds']); $i++) {
        $result = $db->retrieveWisdomById($input['wisdomsIds'][$i]);
        array_push($wisdoms, $result['text']);
    }
    $response['error'] = false;
    $response['wisdoms'] = $wisdoms;
} else {
    $response['error'] = true;
    $response['message'] = "Request not allowed";
}
if (isset($_GET['api'])) {
    echo json_encode($response);
} else {
    return json_encode($response);
}
