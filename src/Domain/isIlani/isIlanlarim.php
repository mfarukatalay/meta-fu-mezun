<?php
include_once '../src/Domain/isIlani/isIlanlarimModeli.php';
include_once '../src/Domain/Database.php';


$alumniID = $_SESSION['mezun']['mezunId'] ;

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);


try {
    $myJob_model = new  MyJobModel($db->getConnection());
    $myJob = $myJob_model->getRow($alumniID);
    $image = $myJob_model->getJobImage($alumniID);
    
    for ($i=0; $i< count($myJob); $i++){
      $myJob[$i]['fotoId'] = $image[$i];
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
    'my_css' => '/css/Alumni/MyJobPage.css',
    'searchBar' => '/css/Alumni/SearchBar.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>



<div class="searchBarBG">

 <div class="containerSB">
   <div class="row no-gutters" style="white-space: nowrap">
     <div class="col-lg-3 col-md-3 col-sm-12 p-0"></div>
     <div class="col-lg-6 col-md-6 col-sm-12 p-0 input-group" style="margin-top: 60px;">
       <input type="search" placeholder="Search..." class="form-control" id="search_item" name="search" value="" />
       <div class="input-group-append">
         <button type="submit" id="search-button" class="btn btn-secondary">
           <i class="fas fa-search"></i>
         </button>
       </div>
     </div>
   </div>
 </div>

</div>
<div class ="container my-5" id='main-body'>

          <h1><b>İş İlanları</b></h1>
        
          <hr>
          <h2>İş İlanlarım
            <span class="d-flex flex-row-reverse"> <a href="addjob" class="btn btn-primary"> + İş İlanı Ekle </a></button></span>
        </h2>
        
          <div class="row justify-content-md-center" id="top"></div>
          <div class="row justify-content-md-center text-center" id="no_result"></div>
          <div class="card-desk">
            <div class="row row-cols-3" id="jobList"></div>
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
              Bu iş ilanını silmek istediğinizden emin misiniz?
              </div>
              <div class="modal-footer">
              <button id="deleteButton"  name ="delete_row" type="button" class="btn btn-danger" data-dismiss="modal">Evet, Sil.</button>
              </div>
              </div>
              </div>
              </div>

              <p id="test"></p>
            
          <span id="pageIndex"></span>
          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <div id="previousPage"></div>
              <div class="pages list-group list-group-horizontal"></div>
              <div id="nextPage"></div>
            </ul>
          </nav>
          <br /><br />
    </div>




<script type="text/javascript">var myJob_array = <?php echo json_encode($myJob) ?>;</script>
<script type="module" src="/js/Alumni/MyJobPageModule.js"></script>

<?php include_once '../src/sablonlar/AltBilgi.php' ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/utility.js"></script>
<script type="text/javascript" src="/js/header.js"></script>
<script type="text/javascript" src="/js/nav.js"></script>







