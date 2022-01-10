<?php

include '../src/Domain/Etkinlik/EtkinlikModeli.php';
include '../src/Domain/Etkinlik/MezunEtkinlikModeli.php';
include '../src/Domain/Database.php';

try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $event_model = new EventModel($db->getConnection());
  $etkinlik = $event_model->getEvent($_GET['etkinlikId']);
  $event_pic_src = $event_model->getEventPicture();
} catch (Exception $e) {
  
  error_log("Exception: " . $e->getMessage());
  include_once '../src/sablonlar/Baslik.php';
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
  exit();
}
?>
<?php
include '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
  'my_css' => '/css/Alumni/EventPage.css',
  'search_bar' => '/css/Alumni/EventDetailsPage.css'
));
?>
<?php
include '../src/sablonlar/nav.php';
?>

<div class="container my-5" id="main-body">
  <div class="row">
    <div class="col-0 col-md-1 col-lg-2">
    </div>
    <div class="col-12 col-md-10 col-lg-8">
      <div class="row align-items-center">
        <div class="col-12">
          <a href="javascript:history.go(-1)" class="btn btn-link back">
            <i class="fas fa-chevron-left fa-2x"></i>
          </a>
          <h3 class="d-inline"><?= $etkinlik['aciklama'] ?></h3>
        </div>
      </div>

      <hr style="
        height: 3px;
        border-width: 0;
        color: rgb(0, 0, 0);
        background-color: black;
      " />
      <div class="row">
        <div class="col-12 col-md-6 d-flex justify-content-center mb-3">
          <img src=<?= $event_pic_src ?> class="image--max-size-100-percent" alt="Event Poster " />
        </div>
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
          <div class='row my-3'>
            <div class='col-4 d-flex justify-content-center'>
              <i class="far fa-calendar-alt fa-3x" style="color: rgb(218, 58, 47); font-size: 50px"></i>
            </div>
            <div class='col-8 d-flex align-items-center'>
              <span class="icon_Text pt-3 pt-sm-0" id="event-date"><?= $etkinlik['tarihSaat'] ?></span>
            </div>
          </div>
          <div class='row my-3'>
            <div class='col-4 d-flex justify-content-center'>
              <i class="far fa-clock fa-3x" style="color: rgb(118, 172, 250); font-size: 50px"></i>
            </div>
            <div class='col-8 d-flex align-items-center'>
              <span class="icon_Text pt-3 pt-sm-0" id="event-time"><?= $etkinlik['tarihSaat'] ?></span>
            </div>
          </div>
          <div class='row my-3'>
            <div class='col-4 d-flex justify-content-center'>
              <i class="fas fa-map-marked-alt fa-3x" style="color: rgb(167, 0, 0); font-size: 50px"></i>
            </div>
            <div class='col-8 d-flex align-items-center'>
              <span class="icon_Text pt-3 pt-sm-0"><?= $etkinlik['adres'] ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-0 col-md-1 col-lg-2">
    </div>
  </div>
  <div class="row">
    <div class="col-0 col-md-1 col-lg-2">
    </div>
    <div class="col-12 col-md-10 col-lg-8">
      <h4 class="pt-3">Etkinlik Açıklaması</h4>
      <div class="jumbotron">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <p style="white-space: pre-wrap;"><?= $etkinlik['tanim'] ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include_once '../src/sablonlar/AltBilgi.php' ?>
<?php
include_once '../src/sablonlar/GenelScript.php'
?>

<script src="/js/Alumni/EventDetailsPage.js"></script>
</body>

</html>