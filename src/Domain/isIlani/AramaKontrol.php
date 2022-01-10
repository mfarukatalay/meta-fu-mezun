<?php
include_once '../src/Domain/isIlani/isIlanlarimModeli.php';
include_once '../src/Domain/Database.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$myJob_model = new  MyJobModel($db->getConnection());

       $searchterm = $_POST['search'];
       $searchJob = $myJob_model->search($searchterm,$_SESSION['mezun']['mezunId'] );
        for($i=0; $i<count($searchJob); $i++){
            $jobID = $searchJob[$i]['isIlaniId'];
            $image = $myJob_model->getSearch($jobID);
            $searchJob[$i]['fotoId'] = $image;
        }


    echo json_encode($searchJob);
exit;
?>