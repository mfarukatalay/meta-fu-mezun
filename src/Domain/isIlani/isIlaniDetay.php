<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/isIlani/isIlaniDetayModeli.php';
?>



<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/JobDetailsPage.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>


<?php

$id = $_GET['jobid'];

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    $job_model = new JobDetailsModel($db->getConnection());
    $all_activities = $job_model->getRow($id);
    $image = $job_model->getJobImage($id);

    $all_activities['fotoId'] = $image[0];

} catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
}
?>

<div class = "container my-5" id='main-body' ></div>
<script type="text/javascript">var job_array = <?php echo json_encode($all_activities) ?>;</script>
<script type="module" src="/js/Alumni/JobDetailsPage.js"></script>
<?php include_once '../src/sablonlar/AltBilgi.php' ?>
<?php include_once '../src/sablonlar/GenelScript.php' ?>

