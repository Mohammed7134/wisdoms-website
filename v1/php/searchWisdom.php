<?php
session_start();
$response2 = array();
require_once '../../includes/DbOperations.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $db = new DbOperations();
  if (isset($_GET["searchText"])) {
    $result = $db->searchWisdom($_GET["searchText"]);
    if ($result !== false) {
      $response2['error'] = false;
      $response2['wisdoms'] = $result;
    } else {
      $response2['error'] = true;
      $response2['message'] = "Something went wrong";
    }
  } else {
    $response2['error'] = true;
    $response2['message'] = "Missing parameter";
  }
} else {
  $response2['error'] = true;
  $response2['message'] = "Request not allowed";
}
echo json_encode($response2);
