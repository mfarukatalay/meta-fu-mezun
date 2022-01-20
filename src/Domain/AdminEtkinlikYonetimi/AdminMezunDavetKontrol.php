<?php
include '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
include '../src/Domain/Database.php';

  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

  function inviteAlumniPhp($alumniId,$eventId,$dateTime){
    global $db;
      $inviteAlumni = new InviteAlumniModel($db->getConnection());	
    $inviteAlumni-> InviteAlumni($alumniId,$eventId,$dateTime);
  };
  
  if(isset($_POST["checkbox"])){
  
  $alumniId = $_POST['mezunId']; $alumniId=explode(",",$alumniId); //split
  $eventId = $_POST['etkinlikId']; $eventId=explode(",",$eventId);
  $dateTime = $_POST['tarihSaat']; $dateTime=explode(",",$dateTime);
  
  for($i=0; $i<count($alumniId);$i++){
    inviteAlumniPhp($alumniId[$i],$eventId[$i],$dateTime[$i]);  
  }
  $invitedAlumni = new InviteAlumniModel($db->getConnection());	
  $updatedAlumniArray = $invitedAlumni->getAll();
  echo json_encode($updatedAlumniArray);
  }
  else if(isset($_POST["mezunId"])){
    inviteAlumniPhp($_POST["mezunId"],$_POST["etkinlikId"],$_POST["tarihSaat"]);  
    $invitedAlumni = new InviteAlumniModel($db->getConnection());	
    $updatedAlumniArray = $invitedAlumni->getAll();
    echo json_encode($updatedAlumniArray);
}
  ?>

