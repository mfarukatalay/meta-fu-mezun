<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/isIlani/isIlanlarimDetayModeli.php';
?>



<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/MyJobDetailsPage.css',
    'searchBar' => '/css/Alumni/SearchBar.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>



<?php

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$myjobid = $_GET['myjobid'];

try {
    $myjob_model = new MyJobDetailsModel($db->getConnection());
    $myjobdetails = $myjob_model->getRow($myjobid);
    $image = $myjob_model->getJobImage($myjobid);
    $myjobdetails['fotoId'] = $image[0];

} catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
}
?>

<div class = "container my-5" id='main-body'></div>
<br>

<script type="text/javascript">var job_array = <?php echo json_encode( $myjobdetails) ?>;</script>
<script type="module" src="/js/Alumni/MyJobDetailsPage.js"></script>
<?php include_once '../src/sablonlar/AltBilgi.php' ?>
<?php include_once '../src/sablonlar/GenelScript.php' ?>