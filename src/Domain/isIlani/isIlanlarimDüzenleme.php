<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/isIlani/isIlanlarimDüzenlemeModeli.php';
?>

<?php 

include '../src/araclar/FotoYukleme.php';
$myjobid = $_GET['myjobid'];
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$job_model = new EditMyJobModel($db->getConnection());
if(isset($_POST['Submit'])) {
  
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $date = date('y-m-d H:i:s');
  $postedDateTime = date(DATE_ATOM, strtotime($date));

  $jobId = $myjobid;
  $alumniId = $_SESSION['mezun']['mezunId'] ;        
  $title = $_POST['jobtitle'];
  $description = $_POST['tanim'];
  $salary = $_POST['maas'];
  $email = $_POST['email'];
  $postedDateTime = $postedDateTime;    
  $imageId = $myjobid;
  $company = $_POST['sirket'];
  $location = $_POST['adres'];

  $job_model->editJob($jobId,$alumniId,$title,$description,$salary,$email,$postedDateTime,$imageId,$company,$location);
  
  try{
    if($_FILES["fotoId"]['tmp_name']!=null){
        uploadImage($db->getConnection(),$_FILES["fotoId"],$imageId);
    }

  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }

  header("Location: myjob");

}


$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
  $job_model = new EditMyJobModel($db->getConnection());
  $editjob = $job_model->getRow($myjobid);
  $image = $job_model->getJobImage($myjobid);
  $editjob['imageId'] = $image[0];

} catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
}
?>


<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/EditMyJobPage.css',
    'searchBar' => '/css/Alumni/SearchBar.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>

<div class = "container my-5" id="main-body"></div>

<script type="text/javascript">var job_array = <?php echo json_encode($editjob) ?>;</script>
<script type="text/javascript" src="/js/Alumni/EditMyJobPage.js"></script>
<?php include_once '../src/sablonlar/AltBilgi.php' ?>
<?php include_once '../src/sablonlar/GenelScript.php' ?>