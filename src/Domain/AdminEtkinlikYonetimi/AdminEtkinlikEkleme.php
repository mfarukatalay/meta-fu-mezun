 <?php
  include_once '../src/araclar/Degisken.php' ?>
 <?php
  includeWithVariables('../src/templates/header.php', array(
    'admin_eventPageCreate_css' => '/css/Admin/AdminEtkinlikEklemeSayfasi.css',
    'index' => '/css/Mezun/index.css'
  ));
  ?>
 <?php
  include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
  include_once '../src/Domain/Database.php';
  include_once '../src/araclar/FotoYukleme.php';
  $_SESSION['admin']['adminId'];
  
  try {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $event_model = new Admin_EventModel($db->getConnection());
    $all_activities = $event_model->getAll();
    $allImage = $event_model->getPicture();
    if (!empty($all_activities)) {
      foreach ($all_activities as $activity) {
      }
    }
    for ($i = 0; $i < count($all_activities); $i++) {
      $all_activities[$i]['fotoId'] = $allImage[$i];
    }
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/templates/header.php';
include_once '../src/Domain/General_Pages/server_error.php';
exit();
  }
  ?>
 <?php
  if (isset($_POST['Submit'])) {
    try {
      $addEvent = new createEventModel($db->getConnection());
      $data = $addEvent->getMaxId();
      $eventId = "E-" . ($data + 1);
      $adminId = $_SESSION['admin']['adminId'];        
      $title = $_POST['aciklama'];
      $date = $_POST["date"];
      $time = $_POST["time"];
      $description = $_POST['tanim'];
      $locate = $_POST['adres'];
      if ($_FILES["eventPicture"]['tmp_name'] != null) {
        $imageId = $eventId;
      } else {
        $imageId = "Default";
      }
      $combinedDT = date('Y-m-d H:i', strtotime("$date $time"));
      $addEvent->updateEvent($eventId, $adminId, $title, $combinedDT, $description, $imageId, $locate);
      $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  
      if ($_FILES["eventPicture"]['tmp_name'] != null) {
        uploadImage($db->getConnection(), $_FILES["eventPicture"], $imageId);
      }
      echo '<script>location.href="/admin/event"</script>';
    } catch (Exception $e) {
      // echo "Exception: " . $e->getMessage();
error_log("Exception: " . $e->getMessage());
include_once '../src/templates/header.php';
include_once '../src/Domain/General_Pages/server_error.php';
exit();
    }
  }

  ?>
 <main class="container-fluid height-after-minus-header" id='main-body'>
   <div class="row h-100 justify-content-center align-items-center">
     <div class="col-lg-8 pb-5" id="right-content">
     <br>
       <h1>
         Yeni Etkinlik
       </h1><br>


       <main>
         <form method="post" onsubmit="return checkvalidation()" enctype="multipart/form-data">
           <div class="form-group">
             <label for="formGroupExampleInput">Etkinlik Başlığı :</label>
             <input type="text" class="form-control rounded-0 w-75 p-3" id="aciklama" name="aciklama" value="" placeholder="Enter event title">

             <div class="valid-feedback">Geçerli.</div>
             <div id="contactNumberFeedback" class="invalid-feedback">
               Lütfen etkinliğin başlığını belirtin.
             </div>
           </div>


           <!-- form -->
           <div class="form-group">
             <label for="formGroupExampleInput2">Tarih :</label> <br>
             <input type=date id="date" name="date" value="">
             &nbsp;
             <input type=time id="time" name="time" value="">

             <div id="contactNumberFeedback" class="invalid-feedback">
               Lütfen etkinliğin tarihini ve saatini belirtin.
             </div>
           </div>

           <div class="form-group">
             <label for="formGroupExampleInput2">Açıklama :</label>
             <textarea type="text" class="form-control rounded-0" value="" id="tanim" name="tanim" placeholder="Enter event description" rows="8" style="height:100%;"></textarea>
             <div class="valid-feedback">Geçerli.</div>
             <div id="contactNumberFeedback" class="invalid-feedback">
               Lütfen etkinlik için kısa bir açıklama giriniz.
             </div>
           </div>


           <div class="form-group">
             <label for="formGroupExampleInput2 ">Etkinlik Adresi :</label>
             <input type="text " class="form-control rounded-0 w-75 p-3" value="" id="adres" name="adres" placeholder="Enter location">
             <div class="valid-feedback">Geçerli.</div>
             <div id="contactNumberFeedback" class="invalid-feedback">
               Lütfen etkinliğin adresini belirtin.
             </div>
           </div>

           <div class="w-25 position-relative">
             <label for="phfile">Etkinlik Fotoğrafı:</label>
             <div class="picture-container">
               <div class="picture">
                 <img src="/Assets/imgs/default_events.jpg" id="prevImage" alt="update Image" width="100%">
                 <input type="file" id="wizard-picture" name="fotoId">
                 <input type="file" name="eventPicture" id="eventPicture" class="d-none">
               </div>


             </div>
             <h6 id="choosePictureDescription"></h6>
             <div id="contactNumberFeedback" class="invalid-feedback">
               Lütfen etkinlik için bir fotoğraf ekleyin.
             </div>
           </div>
           <!-- ssave button -->
           <input type="submit" name="Submit" id="saveButton" class="btn btn-primary float-right ml-2" value="Submit"></button>
           <button id="cancelButton" type="button" class="btn btn-outline-secondary float-right" onclick="cancelCreate()">İptal</button>


         </form>
         <!-- modal -->
         <div class="modal fade" id="cancelChangesModal" tabindex="-1" aria-labelledby="cancelChangesModalLabel" aria-hidden="true">
           <div class="modal-dialog">
             <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="cancelChangesModalLabel">Onayla</h5>
                 <button id="closeCancelChangesModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('#cancelChangesModal')">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                 Değişiklikler yaptınız. Devam ederseniz yaptığınız değişiklikler kaydedilmeyecektir.
                  Bu sayfadan ayrılmak istediğinizden emin misiniz?
               </div>
               <div class="modal-footer">
                 <a href="/admin/event"><button type="button" class="btn btn-secondary">Bu Sayfadan
                     Ayrıl</button></a>
                 <button id="stayButton" type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeModal('#cancelChangesModal')">Bu Sayfada
                   Kal</button>
               </div>
             </div>
           </div>
         </div>

         <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
     </div>
     <script type="text/javascript">
       var event_array = <?php echo json_encode($all_activities) ?>;
     </script>
 </main>
 <?php include_once '../src/sablonlar/GenelScript.php' ?>

 </body>
 <br>

 </html>