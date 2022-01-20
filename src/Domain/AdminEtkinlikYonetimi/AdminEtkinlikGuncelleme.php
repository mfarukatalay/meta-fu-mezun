  <?php
  include_once '../src/araclar/Degisken.php' ?>
  <?php
  includeWithVariables('../src/sablonlar/Baslik.php', array(
    'admin_eventPageCreate_css' => '/css/Admin/AdminEtkinlikEklemeSayfasi.css',
    'index' => '/css/Mezun/index.css'
  ));
  ?>
  <?php
  include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
  include_once '../src/Domain/Database.php';
  include_once '../src/araclar/FotoYukleme.php';

  $_SESSION['admin']['adminId'];

  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  try {
    $event_model = new Admin_EventModel($db->getConnection());
    $event_model->getEvent($_GET['etkinlikId']);
    $all_activities = $event_model->getAll();
    $allImage = $event_model->getPicture();
    if (!empty($all_activities)) {
      foreach ($all_activities as $res) {
        if ($res['aciklama'] == $title) {
          $eventId = $res['etkinlik'];
        }
      }
    }
    for ($i = 0; $i < count($all_activities); $i++) {
      $all_activities[$i]['fotoId'] = $allImage[$i];
    }
  } catch (Exception $e) {
    
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }
  ?>

  <?php
  if (isset($_POST['update'])) {
    $eventId = $_GET['etkinlik'];
    $updateTheEvent = new UpdateEventModel($db->getConnection());
    $imageId = $updateTheEvent->getImageId($eventId);
    $adminId = $_SESSION['admin']['adminId'];  
    $title = $_POST['aciklama'];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $description = $_POST['tanim'];
    $locate = $_POST['adres'];
    $combinedDT = date('Y-m-d H:i', strtotime("$date $time"));
    if ($_FILES["eventPicture"]['tmp_name'] != null) {
      $imageId = $eventId;
    }

    $updateTheEvent->updateEvent($eventId, $adminId, $title, $combinedDT, $description, $imageId, $locate);

    if ($_FILES["eventPicture"]['tmp_name'] != null) {
      uploadImage($db->getConnection(), $_FILES["eventPicture"], $imageId); 
    } 
    echo '<script>location.href="/admin/etkinlik"</script>';
  }
  ?>
  <script type="text/javascript">
    var event_array = <?php echo json_encode($all_activities) ?>;
  </script>
  <script type="module" src="/js/Admin/Admin-UpdateEventPage.js"></script>

  <main class="container-fluid height-after-minus-header" id='main-body'>
    <div class="row h-100 justify-content-center align-items-center">
      <div class="col-lg-8 pb-5" id="right-content">
        <br>
        <h1>
          Etkinliği Güncelle <br>
        </h1>

        <form method="post" onsubmit="return checkvalidation()" enctype="multipart/form-data">
          <div id="updateForm">
            <div class="form-group">

              <label for="formGroupExampleInput">Etkinlik Başlığı :</label>
              <input type="text" class="form-control rounded-0 w-75 p-3" id="aciklama" name="aciklama" placeholder="Enter new event title" value="<?php echo "$title"; ?>" required>
              <div class="valid-feedback">Geçerli.</div>
              <div id="contactNumberFeedback" class="invalid-feedback">
                Lütfen etkinliğin başlığını giriniz.
              </div>
            </div>

            <div class="form-group">
              <label for="formGroupExampleInput2">Takvim :</label> <br>
              <input type=date value="" id="date" name="date"> &nbsp;
              <input type=time value="" id="time" name="time">
              <div id="contactNumberFeedback" class="invalid-feedback">
                Lütfen etkinliğin tarihini ve saatini belirtin.
              </div>
            </div>

            <div class="form-group">
              <label for="formGroupExampleInput2">Açıklama :</label>
              <textarea type="text" class="form-control rounded-0" id="tanim" name="tanim" placeholder="Enter new schedule" value="<?php echo $description; ?>" rows="8"></textarea>
              <div class="valid-feedback">Geçerli.</div>
              <div id="contactNumberFeedback" class="invalid-feedback">
                Lütfen etkinlik için kısa bir açıklama giriniz.
              </div>

            </div>

            <div class="form-group">
              <label for="formGroupExampleInput2">Adres :</label>
              <input type="text " class="form-control rounded-0 w-75 p-3" id="adres" name="adres" placeholder="Enter new location" value="<?php echo "$locate"; ?>">
              <div class="valid-feedback">Geçerli.</div>
              <div id="contactNumberFeedback" class="invalid-feedback">
                Lütfen geçerli bir adres giriniz.
              </div>
            </div>

            <?php $img_Path = "../../../../public/Assets/imgs/" ?>
            <div class="w-25 position-relative">
              <label for="phfile">Etkinlik Fotoğrafı:</label>
              <div class="picture-container">
                <div class="picture">
                  <img src="<?php echo "$img_Path$imageId"; ?>" id="prevImage" alt="update Image" width="150" length="150">
                  <input type="file" id="wizard-picture" name="fotoId">
                  <input type="file" name="eventPicture" id="eventPicture" class="d-none">
                </div>
              </div>
              <h6 id="choosePictureDescription"></h6>
              <div id="contactNumberFeedback" class="invalid-feedback">
                Lütfen etkinlik için bir fotoğraf yükleyiniz.
              </div>
            </div>
          </div>
          <input type="submit" name="update" id="saveButton" class="btn btn-primary float-right ml-2"></input>
          <button id="cancelButton" onclick="cancelUpdate()" type="button" class="btn btn-outline-secondary float-right">İptal</button>

        </form>
        <!-- modal -->
        <div class="modal fade" id="cancelChangesModal" tabindex="-1" aria-labelledby="cancelChangesModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="cancelChangesModalLabel">Onayla</h5>
                <button id="closeCancelChangesModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Değişiklikler yaptınız. Devam ederseniz değişiklikler kaydedilmeyecektir.
                Bu sayfadan ayrılmak istediğinizden emin misiniz?
              </div>
              <div class="modal-footer">
                <a href="/admin/event"><button type="button" class="btn btn-outline-secondary">Bu Sayfadan
                    Ayrıl</button></a>
                <button id="stayButton" type="button" class="btn btn-primary" data-dismiss="modal">Bu Sayfada
                  Kal</button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <?php include_once '../src/sablonlar/GenelScript.php' ?>


  </body>

  </html>