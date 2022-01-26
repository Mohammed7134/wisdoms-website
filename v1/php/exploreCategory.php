<?php
if (isset($_GET['api'])) {
  session_start();
}
$response1 = array();
require_once '../../../etc/includes/DbOperations.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $db = new DbOperations();
  if (isset($_GET["categoryId"])) {
    $result = $db->exploreCategory($_GET["categoryId"]);
    if ($result !== false) {
      $response1['error'] = false;
      $response1['wisdoms'] = $result;
    } else {
      $response1['error'] = true;
      $response1['message'] = "Something went wrong";
    }
  }
} else {
  $response1['error'] = true;
  $response1['message'] = "Request not allowed";
}
echo json_encode($response1);
