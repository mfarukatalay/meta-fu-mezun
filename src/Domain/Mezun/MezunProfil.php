<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/Profilim/ProfilModeli.php';

include_once '../src/araclar/Degisken.php';

includeWithVariables('../src/sablonlar/Baslik.php', array(
  'my_css' => '/css/Alumni/MyProfilePage.css',
  'search_bar' => '/css/Alumni/SearchBar.css'
));
include_once '../src/sablonlar/nav.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
 
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  $alumni = new MyProfile($db->getConnection(), $id);

  if ($id == $_SESSION['mezun']['mezunId']) {
    echo "<script> location.href='/myprofile'; </script>";
    exit;
  } else if (!$alumni->isAlumniExist()) {
    include_once '../src/Domain/Genel_Sayfa/sayfa_bulunamadi.php';
    include_once '../src/sablonlar/AltBilgi.php';
    include_once '../src/sablonlar/GenelScript.php';
    exit;
  }
} catch (Exception $e) {
  error_log("Exception: " . $e->getMessage());
  include_once '../src/sablonlar/Baslik.php';
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
  exit();
}
?>
</head>

<body>
  <div id="main-body" class="row mx-0 my-5 justify-content-center">
    <div class="col-12 col-md-10 col-lg-7">
      <div class="row align-items-center">
        <div class="col-12">
          <a href="javascript:history.go(-1)" class="btn btn-link back">
            <i class="fas fa-chevron-left fa-2x"></i>
          </a>
          <h3 class="d-inline">Mezun Profili</h3>
        </div>
      </div>
      <hr style="
            height: 3px;
            border-width: 0;
            color: rgb(0, 0, 0);
            background-color: black;
        " />
      <div class="row mt-3 mb-3 align-items-center">
        <div class="col-sm-5 d-flex align-items-center justify-content-center">
          <div class="w-50 position-relative">
            <div class="rounded-circle overflow-hidden border" style="aspect-ratio: 1/1">
              <img id="profilePicture" src="<?= $alumni->getProfilePicture(); ?>" alt="Profile Picture" class="img-fluid" />
            </div>
          </div>
        </div>
        <div class="col-sm-7 justify-content-center align-items-center pt-3">
          <div class="row mb-3">
            <div class="col-sm-4">İsim:</div>
            <div id="isim" class="col-sm-8"><?= $alumni->getName(); ?></div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4">Cinsiyet:</div>
            <div id="cinsiyet" class="col-sm-8"><?= $alumni->getGender(); ?></div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4">Mezuniyet Yılı:</div>
            <div id="mznYil" class="col-sm-8"><?= $alumni->getGraduatedYear(); ?></div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4">Bölüm:</div>
            <div id="bolum" class="col-sm-8"><?= $alumni->getDepartment(); ?></div>
          </div>
          <?php
          if ($alumni->getIsEmailPublic() == 1) {
            echo '<div class="row mb-3">
                    <div class="col-sm-4">E-mail:</div>
                    <div id="email" class="col-sm-8">' . $alumni->getEmail() . '</div>
                    </div>';
          }
          ?>
        </div>
      </div>
      <div class="container">
        <div class="row mt-5">
          <h4>Biyografi</h4>
          <div class="col-12 rounded bg-grey p-5 mb-2">
            <div id="bio" class="profile__biography_valueContainer_value" style="white-space: pre-wrap;"><?= $alumni->getBiography(); ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  include_once '../src/sablonlar/AltBilgi.php';
  include_once '../src/sablonlar/GenelScript.php';
  ?>

</body>

</html>