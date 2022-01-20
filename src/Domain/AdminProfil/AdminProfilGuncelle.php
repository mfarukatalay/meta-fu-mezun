<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/AdminProfil/AdminModeli.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

$admin = new AdminMyProfile($db->getConnection(), $_SESSION['admin']['adminId']);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profilimi Düzenle - Mezun Sistemi</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/Mezun/Profilim.css">
    <link rel="stylesheet" href="/css/Mezun/ProfilimiDüzenle.css">
    <link rel="stylesheet" type="text/css" href="/css/Mezun/index.css">

</head>

<body>
    <div class="row m-0 justify-content-center align-items-center">
        <div id="editProfileBg"></div>
        <div class="col-lg-6 my-5 p-5 blurContainer">
            <div class="row">
                <h2>Profilimi Düzenle</h2>
            </div>
            <form id="editMyProfileForm" method="POST" action="/api/adminprofile/edit" enctype="multipart/form-data">
                <div class="row mt-2 mb-2 align-items-center pt-5 pb-5 rounded">
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <div class="w-75 position-relative">
                            <div class="picture-container">
                                <div class="picture">
                                    <img src="<?= $admin->getProfilePicture(); ?>" class="picture-src" id="wizardPicturePreview" title="">
                                    <input type="file" id="wizard-picture">
                                    <input type="file" name="profilePicture" id="profilePicture" class="d-none">
                                </div>
                                <h6 id="choosePictureDescription">Fotoğraf Seç</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 justify-content-center align-items-center">
                        <div class="row mb-3">
                            <div class="col-md-4">İsim:</div>
                            <div class="col-md-8">
                                <input id="isim" name="username" type="text" class="form-control" value="<?= $admin->getName(); ?>">
                                <div class="valid-feedback">Geçerli.</div>
                                <div id="emailFeedback" class="invalid-feedback">
                                    En az 5 karakter olmalı
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">E-mail:</div>
                            <div class="col-md-8">
                                <div id="email" name="email" type="email"><?= $admin->getEmail(); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end mt-3">
                    <button id="cancelButton" type="button" class="btn btn-outline-secondary">İptal</button>
                    <button id="saveButton" type="submit" name="submit" form="editMyProfileForm" class="btn btn-primary ml-3">Kaydet</button>
                </div>
            </form>

        </div>
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
                        Değişiklikler yaptınız. Devam ederseniz kaybolacaklar.
                        Bu sayfadan ayrılmak istediğinizden emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <a href="Admin-MyProfilePage.html"><button type="button" class="btn btn-outline-secondary">Bu Sayfadan Ayrıl</button></a>
                        <button id="stayButton" type="button" class="btn btn-primary" data-dismiss="modal">Sayfada Kal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var adminName = "<?= $admin->getName(); ?>";
    </script>
    <?php include_once '../src/sablonlar/GenelScript.php' ?>
    <script type='module' src="/js/Admin/AdminProfilimiDuzenle.js"></script>
</body>

</html>