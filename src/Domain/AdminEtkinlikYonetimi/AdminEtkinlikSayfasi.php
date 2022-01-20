<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
  'searchBar_css' => '/css/Mezun/SearchBar.css',
  'admin_eventPage_css' => '/css/Admin/AdminEtkinlikSayfasi.css',
  'index' => '/css/Mezun/index.css'
));
?>
</head>
<body>
<?php
include_once '../src/Domain/AdminEtkinlikYonetimi/AdminEtkinlikModeli.php';
include_once '../src/Domain/Database.php';
$_SESSION['admin']['adminId'];

global $db;
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
  $event_model = new Admin_EventModel($db->getConnection());
    $all_activities = $event_model->getAll();
    $allImage = $event_model->getPicture();
    $eventNumber = $event_model->getNumberOfEvent();
    for ($i=0; $i< count($all_activities); $i++){
      $all_activities[$i]['fotoId'] = $allImage[$i];
    }
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }
?>
  <script type="text/javascript">var event_array = <?php echo json_encode($all_activities)?>;</script>
  <script type="module" src="/js/Admin/Admin-EtkinlikSayfa.js"></script>
  

  <main class="container-fluid height-after-minus-header" id='main-body'>
    <div class="row h-100">
      <div class="custom-dark-gray px-0" id="left-nav">
      </div>
      <div class="container-fluid" id="right-content">
        <br>
        <div class="col-12">
          <h2 style="font-weight: 600">Etkinlik Listesi</h2>
          </divc>

          <div class="rightop">
            <div class="input-group mb-3">
              <input id="input1" type="text" class="form-control" style="font-weight: 200; font-style: italic"
                placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button id="searchBar" class="btn btn-secondary my-2 my-sm-0" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </div>
          <br />


          <div class="row m-0">
            <div class="btn-group" role="group" aria-label="Third group">
              <a button type="button"
                class="btn btn-primary d-flex justify-content-center align-items-center rounded mr-2"
                href="/admin/create/event"><i class="fa fa-plus fa-2x" aria-hidden="true"
                  style="font-size: 20px; "></i></a>

              <button type="button"
                class="btn btn-outline-danger d-flex justify-content-center align-items-center rounded"
                onclick="DeleteCheckedRow()"><i class="far fa-trash-alt fa-2x" aria-hidden="true"
                  style="font-size: 20px; "></i></button> 
            </div>
          </div>
          <br />

          <div class="table-responsive">
            <table id="myTable" class="table table-striped table-sm something">
              <thead style="background-color:rgb(134, 75, 189);font-weight: 200; color:#ffffff">
                <tr>
                  <th>
                    <div class="custom-control custom-checkbox text-center">
                      <input type="checkbox" class="custom-control-input" id="CheckAllBoxes" onclick="toggle(this);">
                      <label class="custom-control-label" for="CheckAllBoxes"></label>
                    </div>
                  </th>
                  <th>Takvim</th>
                  <th>Başlık</th>
                  <th style="width: 25%">Açıklama</th>
                  <th>Adres</th>
                  <th>Eylem</th>
                </tr>
              </thead>

              <tbody>
              <tr class="rowss"></tr>

              </tbody>
            </table>
          <div class="row justify-content-md-center text-center" id="no_result" style="width:100% "></div>
            
          </div>
          <!-- model -->
          <div class="modal fade p-5" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
          </div>


          <span id="pageIndex"></span>


          <div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Etkinlik</h5>
                  <button type="button" id="closeCancelChangesModalButton" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-body">
                  <img src='' class="mx-auto d-block" alt="name" id="imageTitle" width="200px" height="auto">
                  <br>
                  <b class="m-0"> Başlık : </b>
                  <p id="title"></p>
                  <b class="m-0"> Açıklama : </b>
                  <p id="description" style="white-space: pre-wrap"></p>
                  <b class="m-0"> Konum : </b>
                  <p id="location"></p>
                </div>

                <div class="modal-footer" id="modelEdit">
                  <button type="button" id="editButton" class="btn btn-primary">
                    <i class="fas fa-edit">
                    </i>
                    Düzenle</button>
                </div>
              </div>
            </div>
          </div>

         <div class="modal fade" id="deleteModal" role="dialog">
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Onayla</h5>
              <button id="closeDeleteModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
              Bu etkinliği silmek istediğinizden emin misiniz?
              </div>
              <div class="modal-footer">
              <button id="deleteButton"  name ="delete_row" type="button" class="btn btn-danger" data-dismiss="modal">Evet, Sil.</button>
              </div>
              </div>
              </div>
              </div>

         <div class="modal fade" id="deleteCheckedModal" role="dialog">
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Onayla</h5>
              <button id="closeDeleteModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
              Seçilen etkinlikleri silmek istediğinizden emin misiniz?
              </div>
              <div class="modal-footer">
              <button id="deleteCheckedButton"  name ="delete_row" type="button" class="btn btn-danger" data-dismiss="modal">Evet, Sil.</button>
              </div>
              </div>
              </div>
              </div>

          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item" id="previousPage">
              </li>
              <div class="pages list-group list-group-horizontal">
              </div>
              <li class="page-item" id="nextPage">
              </li>
            </ul>
          </nav>

        </div>
      </div>
  </main>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


</body>
</html>
