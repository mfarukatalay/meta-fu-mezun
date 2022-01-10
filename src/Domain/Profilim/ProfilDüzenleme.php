<?php

include_once '../src/Domain/Database.php';
include_once '../src/Domain/Profilim/ProfilModeli.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    $alumni = new MyProfile($db->getConnection(), $_SESSION["mezun"]['mezunId']);
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    include_once '../src/templates/header.php';
    include_once '../src/Domain/General_Pages/server_error.php';
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profili Düzenle - Mezun Sistemi</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="/Assets/imgs/UM_Logo.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/Alumni/MyProfilePage.css">
    <link rel="stylesheet" href="/css/Alumni/EditMyProfilePage.css">
    <link rel="stylesheet" type="text/css" href="/css/Alumni/index.css">

</head>

<body>
    <div class="row m-0 justify-content-center align-items-center">
        <div id="editProfileBg"></div>
        <div class="col-lg-8 my-5 p-5 blurContainer">
            <div class="row mx-0">
                <h2><b>Profilimi Düzenle</b></h2>
            </div>
            <form id="editMyProfileForm" method="POST" action="/api/myprofile/edit" enctype="multipart/form-data">
                <div class="row mt-3 mb-3 align-items-center">
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <div class="w-50 position-relative">
                            <div class="picture-container">
                                <div class="picture">
                                    <img src=<?= $alumni->getProfilePicture(); ?> class="picture-src" id="wizardPicturePreview" alt="profile picture">
                                    <input type="file" id="wizard-picture">
                                    <input type="file" name="profilePicture" id="profilePicture" class="d-none">
                                </div>
                                <h6 id="choosePictureDescription">Fotoğraf Seç</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 justify-content-center align-items-center">
                        <div class="row mb-3">
                            <div class="col-md-5">İsim:</div>
                            <div id="isim" class="col-md-7"><?= $alumni->getName(); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">Cinsiyet:</div>
                            <div id="cinsiyet" class="col-md-7"><?= $alumni->getGender(); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">Mezuniyet Yılı:</div>
                            <div id="mznYil" class="col-md-7"><?= $alumni->getGraduatedYear(); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">Bölüm:</div>
                            <div id="bolum" class="col-md-7"><?= $alumni->getDepartment(); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">E-mail:</div>
                            <div id="email" class="col-md-7"><?= $alumni->getEmail(); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5 mx-0">
                    <h4>Biyografi</h4>
                    <div class="col-12 rounded bg-grey p-5 mb-2">
                        <textarea class="form-control" name="bio" form="editMyProfileForm" id="biography" rows="10"><?= $alumni->getBiography(); ?></textarea>
                        <div class="valid-feedback">Geçerli.</div>
                        <div id="contactNumberFeedback" class="invalid-feedback">
                            Biyografi Boş Olamaz.
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end mt-3 mx-0">
                    <button id="cancelButton" type="button" class="btn btn-outline-secondary">İptal</button>
                    <button id="saveButton" type="submit" name="submit" value="Submit" class="btn btn-primary ml-3">Kaydet</button>
                </div>
            </form>
        </div>
        <br>
        <div class="modal fade" id="cancelChangesModal" tabindex="-1" aria-labelledby="cancelChangesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelChangesModalLabel">Navigasyonu Onayla</h5>
                        <button id="closeCancelChangesModalButton" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Değişiklikler yaptınız. Devam ederseniz yaptığınız değişiklikler kaydedilmeyecektir.
                        Bu sayfadan ayrılmak istediğinizden emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <a href="/myprofile"><button type="button" class="btn btn-outline-secondary">Bu Sayfadan Ayrıl</button></a>
                        <button id="stayButton" type="button" class="btn btn-primary" data-dismiss="modal">Bu Sayfada Kal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../src/sablonlar/GenelScript.php' ?>
    <script type="text/javascript">
        var alumniBiography = `<?= $alumni->getBiography(); ?>`;
    </script>
    <script type="text/javascript" src="/js/Alumni/EditMyProfilePage.js"></script>

</body>

</html>