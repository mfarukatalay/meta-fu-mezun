<?php
include_once '../src/Domain/isIlani/isIlanlarimModeli.php';
include_once '../src/Domain/Database.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$myJob_model = new  MyJobModel($db->getConnection());

if(isset($_POST['modal'])){
    exit;
    
}

$updatedJob = [];

if( isset($_POST['deleteID']) ){
    $deleteID =  $_POST['deleteID'];
    $searchDelete = $_POST['searchdeleted'];
    $myJob = $myJob_model->deleteJob($deleteID);
    $myJob_model->deleteJobImage($deleteID);

    if(isset($_POST['searchdeleted'])){
        $searchterm = $_POST['searchdeleted'];
        $updatedJob = $myJob_model->search($searchterm,$_SESSION['mezun']['mezunId'] );

        for($i=0; $i<count($updatedJob); $i++){
            $jobID = $updatedJob[$i]['isIlaniId'];
            $image = $myJob_model->getSearch($jobID);
            $updatedJob[$i]['fotoId'] = $image;
        }
    }
    else{
        $updatedJob = $myJob_model->getRow($_SESSION['mezun']['mezunId'] );
        $image = $myJob_model->getJobImage($_SESSION['mezun']['mezunId'] );
        for ($i=0; $i< count($updatedJob); $i++){
            $updatedJob[$i]['fotoId'] = $image[$i];
        }
      
    }
 }
   echo json_encode($updatedJob);
 
?>
