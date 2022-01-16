<?php
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';


if (isset($_POST['deleteMezunId'])) {
  try {
      $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
      $deleteAlumniId = $_POST['deleteMezunId'];
      $deleteAlumni = new DeleteAlumniModel($db->getConnection());
      $deleteTheAlumni = $deleteAlumni->deleteAlumni($deleteAlumniId);
      $all_activities = $deleteAlumni->getAll();
      $allImage = $deleteAlumni->getProfilePicture();
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
