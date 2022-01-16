
  <title><?= $GLOBALS['aciklama']; ?></title>

  
  <?php
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunFotoDenetleyicisi.php';


try {
    $db = new Database(DATABASE_isim, DATABASE_USERisim, DATABASE_PASSWORD);
    $mezun = new mezunListModel($db->getConnection());
    $mezunId=$_GET['mezunId'];
    $mezun->getmezun($mezunId);
  } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }

try {
    $mezunList_model = new mezunListModel($db->getConnection());
    $all_activities = $mezunList_model->getAll();
    $allImage = $mezunList_model->getProfilePicture();
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
    'index' => '/css/Mezun/index.css'
));
?>

<?php
$prevmezunId=$_GET['mezunId'];
if(isset($_POST['update'])) {
    try{
  $updateThemezun = new  UpdatemezunModel($db->getConnection());	
  $isim = $_POST['isim'];
  $cinsiyet =$_POST["cinsiyet"];
  $bolum =$_POST["bolum"];
  $tcNo = $_POST['tcNo'];
  $fotoId = $prevmezunId;
  $mznYil = $_POST['mznYil'];
  $bio = $_POST['bio'];
  $updateThemezun->updateMezun($prevMezunId,$isim,$cinsiyet,$bolum,$tcNo,$mznYil,$bio,$fotoId);
  echo "<script>window.location = '/admin/mezunliste'</script>";
    if($_FILES["foto"]['tmp_isim']!=null){
        uploadImage($db->getConnection(),$_FILES["foto"],$fotoId);
    }
} catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
}
}
?>


<head>
    
    <link rel="shortcut icon" href="/Assets/imgs/Fu_Logo.ico" type="image/x-icon">  
    <title>Mezun Profili Düzenle - Mezun Sistemi</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../css/Mezun/Profilim.css">
    <link rel="stylesheet" href="../css/Mezun/ProfilimiDüzenle.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="../css/Mezun/index.css">

</head>

<body>
    <main class="container-fluid height-after-minus-header" id='main-body'>
        <div class="row h-100">

            <div id="main-body" div class="container" id="right-content">
                <div class="row mx-0">
                    <h2>Mezun Profili Düzenle</h2>
                </div>
                <form id="editMyProfileForm" method="post" enctype="multipart/form-data">
                    <div class="row mt-3 mb-3 align-items-center">
                        <div class="col-sm-5 d-flex align-items-center justify-content-center">
                            <div class="w-50 position-relative">
                                <div class="picture-container">
                                    <div class="picture">
                                        <img src="" class="picture-src"
                                            id="wizardPicturePreview" title="">
                                            <input type="file" id="wizard-picture">
                                            <input type="file" isim="foto" id="profilePicture" class="d-none">
                                    </div>
                                    <h6 id="choosePictureDescription">Fotoğraf Seç :</h6>
                                </div>
                            </div>
                        </div>
                        <!-- mezun isim -->
                        <div class="col-sm-7 justify-content-center align-items-center">
                            <div class="row mb-3">
                                <div class="col-sm-4">İsim:</div>
                                <div class="col-sm-8">
                                    <input method= "post" type="text" id="isim" isim="isim" class="form-control" >
                                    <div class="valid-feedback">Geçerli.</div>
                                    <div id="contactNumberFeedback" class="invalid-feedback">
                                        Lütfen Mezunların Adını Girin..
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">Cinsiyet:</div>
                                <div class="col-sm-8">
                                    <select id="cinsiyet" class="rounded" type="text" isim="cinsiyet"
                                        style="color:#495057; font-family: 'Poppins';">
                                        <option>Erkek</option>
                                        <option>Kadın</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">TC Kimlik No:</div>
                                <div class="col-sm-8">
                                    <input type="tel" id="tcNo" isim="tcNo" class="form-control">
                                    <div class="valid-feedback">Geçerli.</div>
                                    <div id="contactNumberFeedback" class="invalid-feedback">
                                        Lütfen Geçerli Bir TC Kimlik Numarası Giriniz.
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">Mezuniyet Yılı:</div>
                                <div class="col-sm-8">
                                    <input type="text" isim="mznYil" id="mznYil" class="form-control">
                                    <div class="valid-feedback">Geçerli.</div>
                                    <div id="contactNumberFeedback" class="invalid-feedback">
                                        Lütfen Geçerli Mezuniyet Yılını Giriniz.
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">Bölüm:</div>
                                <div class="col-sm-8">
                                    <select id="bolum" class="rounded" type="text" isim="bolum"
                                        style="color:#495057; font-family: 'Poppins'; outline: none; ">
                                        <option>Yazılım Mühendisliği</option>
                                       

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">E-mail:</div>
                                <div class="col-sm-8">
                                    <p type="email" id="email" isim="email"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <h4>Biyografi</h4>
                        <div class="col-12 rounded bg-grey p-5 mb-2">
                            <textarea id="bio" isim ="bio"class="form-control" id="exampleFormControlTextarea1" rows="10">
                    </textarea>
                            <div class="valid-feedback">Geçerli.</div>
                            <div id="contactNumberFeedback" class="invalid-feedback">
                                Biyografi Boş Olamaz.
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <button id="cancelButton" type="button" class="btn btn-outline-secondary">İptal</button>
                        <button type="submit" isim="update" class="btn btn-primary ml-3">Kaydet</button>
                    </div>
                </form>
    </main>
    <div class="modal fade" id="cancelChangesModal" tabindex="-1" aria-labelledby="cancelChangesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelChangesModalLabel">Onayla</h5>
                    <button id="closeCancelChangesModalButton" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Değişiklikler yaptınız. Devam ederseniz değişiklikler kaydedilmeyecektir.
					Bu sayfadan ayrılmak istediğinizden emin misiniz?
                </div>
                <div class="modal-footer">
                    <a href="/admin/mezunlist"><button type="button" class="btn btn-outline-secondary">Bu Sayfadan Ayrıl</button></a>
                    <button id="stayButton" type="button" class="btn btn-primary" data-dismiss="modal">Bu Sayfada Kal</button>
                </div>
            </div>
        </div>
    </div> <br>
    </div>
    <script type="text/javascript">var mezun_array = <?php echo json_encode($all_activities) ?>;</script>
    <script type="module" src="/js/Admin/Admin-MezunProfilDuzenle.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
        <?php include_once '../src/sablonlar/GenelScript.php'?>
