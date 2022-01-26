<?php
session_start();
$response = array();
if (isset($_SESSION['logged in'])) {
    $logState = $_SESSION['logged in'];
    require_once '../../../etc/includes/DbOperations.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $logState == "true") {

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array

        if (isset($input['wisdoms'])) {

            $db = new DbOperations();
            $result = $db->createWisdoms($input['wisdoms']);
            if ($result == true) {
                $response['error'] = false;
                $response['message'] = 'Wisdoms created successfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Some error occurred';
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Parameters are missing';
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Request not allowed";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Not Logged In";
}
echo json_encode($response);
