<?php
include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
include_once '../src/Domain/Database.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$alumni_model = new AlumniModel($db->getConnection());
$result = $alumni_model->getAll();
for($i=0; $i<count($result); $i++){
  $alumniID = $result[$i]['mezunId'];
  $image = $alumni_model->getSearch($alumniID);
  $result[$i]['fotoId'] = $image;
}

if(isset($_POST['status'])){
    if($_POST['status']!="All"){
      $statusTerm = $_POST['status'];
      $alumniStatus = $alumni_model->searchStatus($statusTerm,$_POST['etkinlikId']);      
      for($i=0; $i<count($alumniStatus); $i++){
        $alumniID = $alumniStatus[$i]['mezunId'];
        $image = $alumni_model->getSearch($alumniID);
        $alumniStatus[$i]['fotoId'] = $image;
      }
      $result=$alumniStatus; 
      $result=interceptArray($result,$alumniStatus);
    }

    if($_POST['bolum']!="All"){
        $departmentTerm = $_POST['bolum'];
        $alumniDepartment = $alumni_model->searchDepartment($departmentTerm);
        for($i=0; $i<count($alumniDepartment); $i++){
            $alumniID = $alumniDepartment[$i]['mezunId'];
            $image = $alumni_model->getSearch($alumniID);
            $alumniDepartment[$i]['fotoId'] = $image;
      }
      $result=interceptArray($result,$alumniDepartment);
    }
    
    if($_POST['search']!=""){
      $searchterm = $_POST['search'];
      $searchAlumni = $alumni_model->search($searchterm);
     for($i=0; $i<count($searchAlumni); $i++){
       $alumniID = $searchAlumni[$i]['mezunId'];
       $image = $alumni_model->getSearch($alumniID);
       $searchAlumni[$i]['fotoId'] = $image;
     }     
     $result=interceptArray($result,$searchAlumni);
   }
  }
  echo json_encode($result);
      function interceptArray($result, $new){
        $newArray=array();
        if(empty($result)||empty($new)){
          return $newArray;
        }
        foreach($result as $array){
          foreach($new as $eachNew){
            if($array['mezunId']==$eachNew['mezunId']){
              array_push($newArray,$array);
            }
          }
        }
        $result=$newArray;
        return $newArray;
      }
?>
    