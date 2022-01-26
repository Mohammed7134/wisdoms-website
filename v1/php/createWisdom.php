<?php
session_start();
$response = array();
if (isset($_SESSION['logged in'])) {
    $logState = $_SESSION['logged in'];
    require_once '../../../etc/includes/DbOperations.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $logState == "true") {

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array

        if (isset($input['wisdom']) && isset($input['categories'])) {

            $db = new DbOperations();
            $result = $db->createWisdom($input['wisdom'], $input['categories']);
            if ($result == false) {
                $response['error'] = true;
                $response['message'] = 'Some error occurred';
            } else {
                $response['error'] = false;
                $response['wisdom'] = $result;
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
