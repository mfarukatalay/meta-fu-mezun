<?php
include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
include_once '../src/Domain/Database.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$event_model = new Admin_EventModel($db->getConnection());

if(isset($_POST['search'])){
       $searchterm = $_POST['search'];
       $searchEvent = $event_model->search($searchterm);
      for($i=0; $i<count($searchEvent); $i++){
        $eventID = $searchEvent[$i]['etkinlikId'];
        $image = $event_model->getSearch($eventID);
        $searchEvent[$i]['fotoId'] = $image;
      }      
        echo json_encode($searchEvent);
exit;
    }
    ?>