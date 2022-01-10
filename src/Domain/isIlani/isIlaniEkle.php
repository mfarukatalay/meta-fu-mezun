<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/isIlani/isIlanModeli.php';
?>

<?php
include_once '../src/araclar/FotoYukleme.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);


if (isset($_POST['Submit'])) {

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = date('y-m-d H:i:s');
    $postedDateTime = date(DATE_ATOM, strtotime($date));
    $addJob_model = new  AddJobModel($db->getConnection());

    $data = $addJob_model->getMaxId();
    $jobId = "J-" . ($data + 1);
    $alumniId = $_SESSION['mezun']['mezunId'];
    $title = $_POST['aciklama'];
    $description = $_POST['tanim'];
    $salary = $_POST['maas'];
    $email = $_POST['email'];
    $postedDateTime = $postedDateTime;
    $jobImage = $jobId;
    $company = $_POST['sirket'];
    $location = $_POST['adres'];

    $addJob_model->addJobs($jobId, $alumniId, $title, $description, $salary, $email, $postedDateTime, $jobImage, $company, $location);

    try {
        if ($_FILES["jobImage"]['tmp_name'] != null) {
            uploadImage($db->getConnection(), $_FILES["jobImage"], $jobImage);
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        include_once '../src/sablonlar/Baslik.php';
        include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
        exit();
    }




    header("Location: myjob");
}
?>


<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/AddJobPage.css',
    'searchBar' => '/css/Alumni/SearchBar.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>



<title>İş İlanı Ekle - Mezun Sistemi</title>
</head>

<body>
    <div class="row m-0 justify-content-center align-items-center">
        <div class="col-10 col-lg-7 py-5" id='main-body'>
            <h3 class="mb-4">Yeni İş İlanı Ekle</h3>
            <div id="form"></div>
        </div>

        <script type="text/javascript" src="/js/Alumni/AddJobPage.js"></script>
        <?php include_once '../src/sablonlar/AltBilgi.php' ?>
        <?php include_once '../src/sablonlar/GenelScript.php' ?>