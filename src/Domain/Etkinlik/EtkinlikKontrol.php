<?php
include_once '../src/Domain/Etkinlik/EtkinlikModeli.php';
include_once '../src/Domain/Database.php';

try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $event_model = new EventModel($db->getConnection());
} catch (Exception $e) {
  error_log("Exception: " . $e->getMessage());
  include_once '../src/sablonlar/Baslik.php';
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
  exit();
}
if (isset($_GET['search'])) {
  header('Content-type: application/json');
  $arr = array("Message" => "Success Request");
  echo json_encode($arr);
} else {
  http_response_code(400);
  header('Content-type: application/json');
  $arr = array("Error Message" => "Bad Request");
  echo json_encode($arr);
}
