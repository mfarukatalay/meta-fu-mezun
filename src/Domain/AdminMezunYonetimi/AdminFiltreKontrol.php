<?php
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';


if (isset($_POST['isim'])) {
    $name = $_POST['isim'];
    $department = $_POST['bolum'];
    $status = $_POST['status'];
    try {
        $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        $search = new  AlumniListModel($db->getConnection());	
        $searchSearch = $search-> search($name, $department, $status);
        for($i=0; $i<count($searchSearch); $i++){
            $alumniId = $searchSearch[$i]['mezunId'];
            $image = $search->getSearch($alumniId);
            $searchSearch[$i]['fotoId'] = $image;
          }  
    } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
      echo json_encode($searchSearch);
  }

  ?>