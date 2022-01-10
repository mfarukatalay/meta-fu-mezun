<?php
include_once '../src/Domain/Etkinlik/MezunEtkinlikModeli.php';
include_once '../src/Domain/Database.php';
try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $alumni_event_model = new AlumniEventModel($db->getConnection());
} catch (Exception $e) {
  error_log("Exception: " . $e->getMessage());
  include_once '../src/sablonlar/Baslik.php';
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
  exit();
}

if (isset($_POST['etkinlikId']) && isset($_POST['column'])) {
  header('Content-type: application/json');
  if ($_POST['column'] === 'mznBildirim') {
    $alumni_event_model->setNotificationClosedTrue($_POST['etkinlikId']);
    $arr = array("Message" => "mznBildirim updated.");
    echo json_encode($arr);
  } else if ($_POST['column'] === 'mznGorüntüleme') {
    $alumni_event_model->setViewedByAlumniTrue($_POST['etkinlikId']);
    $arr = array("Message" => "mznGorüntüleme updated.");
    echo json_encode($arr);
  }
} else {
  http_response_code(400);
  header('Content-type: application/json');
  $arr = array("Error Message" => "Bad Request");
  echo json_encode($arr);
}

