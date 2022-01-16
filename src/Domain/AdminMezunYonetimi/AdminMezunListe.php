


<?php

include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';


try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $alumniList_model = new AlumniListModel($db->getConnection());
  $all_activities = $alumniList_model->getAll();
  $allImage = $alumniList_model->getProfilePicture();
  for ($i=0; $i< count($all_activities); $i++){
    if($allImage[$i] == null){
      $all_activities[$i]['fotoId'] = "/Assets/imgs/default_user.png";
    }else
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
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Mezun/isIlaniDetaySayfasi.css'
));
?>

  <script type="text/javascript">var alumni_array = <?php echo json_encode($all_activities) ?>;</script>
  <script type="module" src="../js/Admin/Admin-MezunListe.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet" />
  <link rel="shortcut icon" href="/Assets/imgs/fu_Logo.ico" type="image/x-icon">  
  <link rel="stylesheet" type="text/css" href="../css/Admin/Admin-MezunListe.js">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="../css/Mezun/index.css">

<title>Mezun Listesi</title>
<body>

  <main class="container-fluid height-after-minus-header" id='main-body'>
    <div class="row h-100">
      <div class="custom-dark-gray px-0" id="left-nav">
      </div>
      <div class="container-fluid" id="right-content">
        <div class="row col-12">
          <h2 class='col-12 p-3 font-weight-bold'>Mezun Listesi</h2>
          <form class='col-12'>
            <div class='row bg-light p-4 m-1'>
              <div class="form-group col-6">
                <label for="exampleFormControlSelect1">Durum</label>
                <select class="form-control" id="status" >
                  <option>Hepsi</option>
                  <option>Onaylandı</option>
                  <option>Onay Bekleyen</option>
                </select>
              </div>
              <div class="form-group col-6">
                <label for="exampleFormControlSelect1">Bölüm</label>
                <select class="form-control" id="bolum" >
                  <option>Hepsi</option>
                  <option>Software Engineering</option>
                  <option>Artificial Intelligence</option>
                  <option>Information System</option>
                  <option>Data Science</option>
                  <option>Multimedia</option>
                  <option>Computer System and Network</option>
                </select>
              </div>
              <div class='col-12 mt-2 d-flex flex-row-reverse'>
                <button id="clearAll" class="btn text-white custom-dark-purple" >Hepsini Temizle</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row col-12">
          <form class="col-12 my-2">
            <div class="row">
              <div class="col-7">
                <button id="delete" name="deleteMultipleRow" type="button" class="btn btn-outline-danger" onclick="deleteCheckedRow()">
                  
                <a href="#" role="button">
                    <i class="far fa-trash-alt text-danger" aria-hidden="true" style="font-size: 20px;"></i>
                  </a>
                </button>
              </div>
              <div class="col-5 input-group mb-3">
                <input id="input1" type="text" class="form-control" style="font-weight: 200; font-style: italic"
                  placeholder="Search Alumni's Name" aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button id="searchBar" class="btn btn-secondary my-2 my-sm-0" type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                  </button>
                </div>

              </div>
            </div>
          </form>
          <div class="table-responsive col-12">
            <table id="myTable" class="table table-striped table-sm table-bordered">
              <thead style="font-weight: 200; color:#ffffff" class="custom-dark-purple">
                <tr>
                  <th class="text-center">
                    <div class="custom-control custom-checkbox">
                      <input onclick="toggle(this);" type="checkbox" class="custom-control-input"
                        id="CheckAllBoxes"></input>
                      <label class="custom-control-label" for="CheckAllBoxes"></label>
                    </div>
                  </th>
                  <th>Profil Fotoğrafı</th>
                  <th>İsim</th>
                  <th>Bölüm</th>
                  <th>Durum</th>
                  <th class="text-center">Sil</th> 
                </tr>
              </thead>
              <tbody>
              </div>
              </tbody>
          </table>
          <div class="row justify-content-md-center text-center" id="searchNotFound"></div>
          </div>
      </div>

      <!-- delete modal -->
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
              Silmek istediğinizden emin misiniz?
              </div>
              <div class="modal-footer">
              <button id="deleteButton"  name ="delete_row" type="button" class="btn btn-danger" data-dismiss="modal">Evet, Sil.</button>
              </div>
              </div>
              </div>
              </div>

      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Profil</h5>
                        <button type="button" id="closeInfoModal" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <img src="" id="image" class="mx-auto d-block" alt="isim" width="150px"
                          height="auto">
                        <div class="col">
                          <div class="row mb-2">
                            <div class="col-4">İsim:</div>
                            <div id="isim" class="col-8"></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">Cinsiyet:</div>
                            <div id="cinsiyet" class="col-8"></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">Mezuniyet Yılı:</div>
                            <div id="mznYil" class="col-8"></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">Bölüm:</div>
                            <div id="bolum" class="col-8"></div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">E-mail:</div>
                            <div id="email" class="col-8"></div>
                          </div>

                          <div class="row mb-2">
                            <div class="col-4">TC Kimlik No:</div>
                            <div id="tcNo" class="col-8"></div>
                          </div>

                          <div class="row mb-2">
                            <div class="col-4">Hesap Durumu:</div>
                            <div id="accStatus" class="col-8"></div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">

                        <button id="update" type="button" class="btn btn-primary" data-dismiss="modal"
                          onclick="location.href ='/admin/MezunProfilEdit?mezunId='+ getMezunId()">
                          <i class="fas fa-edit">
                          </i>Düzenle</button>
                      <button id="approve" name="approve" type="submit" class="btn btn-info d-flex justify-content-center align-items-center" onclick="approve()">Onayla</button>
                      </div>
                    </div>
                  </div>
                </div>
                 <nav aria-label="Page navigation example" id="pagination">
            <ul class="pagination justify-content-center">
              <li class="page-item" id="previousPage">
              </li>
              <div class="pages list-group list-group-horizontal">
              </div>
              <li class="page-item" id="nextPage">
              </li>
            </ul>
          </nav>
  </main>

  <script type='text/javascript' src='../js/Admin/addLeftNav.js'></script>
  <?php include_once '../src/sablonlar/GenelScript.php';?>
