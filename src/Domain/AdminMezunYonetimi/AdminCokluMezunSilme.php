<?php
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';


if (isset($_POST['listOfDeleteMezunId'])) {
  $alumniId = $_POST['listOfDeleteMezunId'];
  $alumniId = explode(",",$alumniId); 
  try {
      $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
      $deleteMultipleAlumni = new  DeleteAlumniModel($db->getConnection());
      for($i=0; $i<count($alumniId);$i++){	
        $deleteMultipleAlumni-> deleteAlumni($alumniId[$i]);
        }
        $all_activities = $deleteMultipleAlumni->getAll();
        $allImage = $deleteMultipleAlumni->getProfilePicture();
    } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
      for ($i=0; $i< count($all_activities); $i++){
        if($allImage[$i] == null){
          $all_activities[$i]['fotoId'] = "/Assets/imgs/default_user.png";
        }else
        $all_activities[$i]['fotoId'] = $allImage[$i];
      }
      echo json_encode($all_activities);
  }
