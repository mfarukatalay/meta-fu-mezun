<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
include '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli';

try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $event_model = new Admin_EventModel($db->getConnection());
  $alumni_list_model = new AlumniListModel($db->getConnection());
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
  'editprofile_css' => '/css/Mezun/ProfilimiDuzenle.css',
  'admin_homepage_css' => '/css/Admin/AdminAnasayfa.css',
  'index' => '/css/Mezun/index.css'
));
?>

<title>Anasayfa - Mezun Platformu</title>
</head>

<body>
  <main class="container-fluid height-after-minus-header" id='main-body'>

    <div class="row h-100">
      <div class="custom-dark-gray px-0" id="left-nav">
      </div>
      <div class="container-fluid" id="right-content">

        <div class="m-2 bg-white">
          <br><br>

          <div class="container">
            <h2 class="alert-heading"><b>Hoşgeldiniz!</b></h2>
            <hr>

            <div class="row p-2 justify-content-between">
              <div class="col-lg-3 alert alert-success rounded p-5 m-2">
                <div class="row justify-content-center">
                  <i class="d-flex align-items-center justify-content-center fas fa-users fa-2x col-lg-2"></i>
                  <h5 class="col-lg-10 text-center">Onaylı Mezunlar</h5>
                </div>
                <h1 class="text-center" id="approvedAlumni"><?= $alumni_list_model->getNumberOfApprovedAlumni(); ?></h1>
              </div>
              <div class="col-lg-3 alert alert-danger rounded p-5 m-2">
                <div class="row justify-content-center">
                  <i class="d-flex align-items-center justify-content-center fas fa-users fa-2x col-lg-2"></i>
                  <h5 class="col-lg-10 text-center">Onaylanmamış Mezunlar</h5>
                </div>
                <h1 class="text-center" id="unapprovedAlumni"><?= $alumni_list_model->getNumberOfUnapprovedAlumni(); ?></h1>
              </div>
              <div class="col-lg-3 alert alert-warning rounded p-5 m-2">
                <div class="row justify-content-center">
                  <i class="d-flex align-items-center justify-content-center far fa-calendar-alt fa-2x col-lg-2"></i>
                  <h5 class="col-lg-10 text-center">Etkinlik Sayısı</h5>
                </div>
                <h1 class="text-center" id="numberOfEvents"><?= $event_model->getNumberOfEvent(); ?></h1>
              </div>
            </div>
            <div class="row p-2 justify-content-between">
            </div>

          </div>

        </div>
      </div>
  </main>
  <?php include_once '../src/sablonlar/GenelScript.php'?>
</body>