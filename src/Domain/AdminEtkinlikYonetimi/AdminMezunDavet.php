
<?php
 $eventId=$_GET['etkinlikId'];
  include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
  include_once '../src/Domain/Database.php';
  ?>
  <?php
  include_once '../src/araclar/Degisken.php' ?>
  <?php
  includeWithVariables('../src/sablonlar/Baslik.php', array(
    'asmin_inviteAlumniPage_css' => '/css/Admin/AdminMezunDavet.css',
    'admin_alumniListPage_css' => '/css/Admin/AdminMezunListeSayfasi.css',
    'index' => '/css/Mezun/index.css'
  ));
  
  $_SESSION['admin']['adminId'];

  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

  try {
    $event_model = new Admin_Alumni_EventModel($db->getConnection());
    $all_activities = $event_model->getAll();
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }

  try {
    $event = new Admin_EventModel($db->getConnection());
    $event->getEvent($eventId);
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }

   try {
    $event_model = new AlumniModel($db->getConnection());
    $all_alumni = $event_model->getAll();
    $allImage = $event_model->getPicture();
   
    for ($i=0; $i< count($all_alumni); $i++){
      $all_alumni[$i]['fotoId'] = $allImage[$i];
    }
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }
?>

<nav></nav>
  <main class="container-fluid height-after-minus-header" id='main-body'>
    <div class="row h-100">
      <div class="custom-dark-gray px-0" id="left-nav">

      </div>
      <div class="container-fluid" id="right-content">
        <div class="row col-12">
          <h3 class='col-12 p-3'>Mezunları Davet Et</h3>
          <form class='col-12'>
            <div class='row bg-light p-4 m-1'>
              <div class="form-group col-6">
                <!-- filter status -->
                <label for="exampleFormControlSelect1">Durum</label>
                <select class="form-control" id="status">
                  <option>Tümü</option>
                  <option>Davet Edildi</option>
                  <option>Davet Edilmedi</option>
                </select>
              </div>
              <div class="form-group col-6">
                <!-- filter department -->
                <label for="exampleFormControlSelect1">Bölüm</label>
                <select class="form-control" id="department">
                  <option>Tümü</option>
                  <option>Software Engineering</option>
                  <option>Artificial Intelligence</option>
                  <option>Information System</option>
                  <option>Data Science</option>
                  <option>Multimedia</option>
                  <option>Computer System and Network</option>
                </select>
              </div>

              <div class='col-12 mt-2 d-flex flex-row-reverse'>
                <button type="submit" id="clearAll" class="btn text-white custom-dark-purple">Hepsini Temizle</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row col-12">
          <form class="col-12 my-2">
            <div class="row">
              <div class="col-7">
                <button type="button" class="btn btn-info"  onclick='inviteCheckedAlumni()'>
                  <i class="fas fa-user-plus" aria-hidden="true"
                      style="font-size: 20px;"></i>
                </button>
              </div>
              <div class="col-5 input-group mb-3">
                <input id="input1" type="text" class="form-control" 
                style="font-weight: 200; font-style: italic" placeholder="Search"
                aria-label="Search"  aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button id="searchBar" class="btn btn-secondary my-2 my-sm-0" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </div>
              </div>
            </div>
          </form>

          <div class="table-responsive col-12" >
          
            <table  id="myTable" class="table table-striped table-sm table-bordered">
              <thead style="font-weight: 200; color:#ffffff" class="custom-dark-purple">
                <tr>
                  <th class="text-center">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="CheckAllBoxes" onclick="toggle(this);">
                      <label class="custom-control-label" for="CheckAllBoxes"></label>
                    </div>
                  </th>
                  <th>Profil</th>
                  <th>İsim</th>
                  <th>Bölüm</th>
                  <th>Durum</th>
                  <th class="text-center">S</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

            <div class="col-12 d-flex justify-content-end" id='invideAndDone'>
          </div>
          <div class="row justify-content-md-center text-center" id="no_result"></div>
          <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
              <li class="page-item" id="previousPage"></li>
              <div class="pages list-group list-group-horizontal"></div>
              <li class="page-item" id="nextPage"></li>
            </ul>
          </nav>
          </div>
        </div>
        <br>
      </div>
  </main>
  
  <?php include_once '../src/sablonlar/GenelScript.php'?>
</body>
</html>