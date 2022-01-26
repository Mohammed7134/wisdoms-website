<?php
session_start();
$response = array();
if (isset($_SESSION['logged in'])) {
    $logState = $_SESSION['logged in'];
    require_once '../../../etc/includes/DbOperations.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $logState == "true") {

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array

        if (isset($input['wisdomId'])) {

            $db = new DbOperations();
            $result1 = $db->addWisdomDeleted(intval($input['wisdomId']));
            $result2 = $db->removeWisdom(intval($input['wisdomId']));
            if ($result1 == true && $result2 == true) {
                $response['error'] = false;
                $response['message'] = 'Wisdom deleted successfully';
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
