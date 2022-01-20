<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/AdminProfil/AdminModeli.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

$admin = new AdminMyProfile($db->getConnection(), $_SESSION['admin']['adminId']);
?>

<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'index' => '/css/Mezun/index.css'
));
?>

<title>Profilim - Mezun Sistemi</title>


</head>

<body>
    <main class="container-fluid height-after-minus-header" id='main-body'>
        <div class="row h-100">
            <div class="custom-dark-gray px-0" id="left-nav">

            </div>
            <div id="right-content" class="container-fluid" style="background:url(/Assets/imgs/JAN_17_TECNOLOGIA_EEZY_01.png);background-size:70%;background-repeat: no-repeat;background-position:bottom right;">
                <div class="container mt-5">
                    <?php
                    if (isset($_GET['updated'])) {
                        echo '
                        <div class="row alert alert-success alert-dismissible fade show align-items-center" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>Your information is updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                    if (isset($_GET['changepassword']) && $_GET['changepassword'] == 'fail') {
                        
                        echo "
                        <script type='text/javascript'>
                            window.onload = function(){
                                $('#changePasswordModal').modal('show');
                                document.getElementById('oldPassword').classList.add('is-invalid');                
                            }
                        </script>";
                    }elseif (isset($_GET['changepassword']) && $_GET['changepassword'] == 'success') {
                        echo '
                        <div class="row alert alert-success alert-dismissible fade show align-items-center" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>Your password is updated successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                    if (isset($_GET['error'])) {
                        foreach ($_GET['error'] as $error) {
                            echo '
                            <div class="row alert alert-danger alert-dismissible fade show align-items-center" role="alert">
                                <i class="fas fa-times-circle mr-2"></i>' . $error . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                        }
                    }
                    ?>
                    <div class="row justify-content-between">
                        <h2>Profilim</h2>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog mr-2"></i>
                                Ayarlar
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="/adminprofile/edit" class="dropdown-item">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Profili Düzenle
                                </a>
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal">
                                    <i class="fas fa-lock-alt mr-2"></i>
                                    Şifre Değiştir
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-3 justify-content-center align-items-center pt-5 pb-5 rounded bg-white border shadow" style="border-top: 8px solid #6f49ab !important;">
                        <div class="col-sm-5 d-flex align-items-center justify-content-center">
                            <div class="w-50 position-relative">
                                <div class="rounded-circle overflow-hidden border" style="aspect-ratio: 1/1;">
                                    <img id='profilePicture' src=<?= $admin->getProfilePicture(); ?> alt="Profile Picture" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7 justify-content-center align-items-center text-dark">
                            <div class="row mb-3">
                                <div class="col-sm-3 h5">İsim:</div>
                                <div id="isim" class="col-sm-9 h5"><?= $admin->getName(); ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3 h5">E-mail:</div>
                                <div id="email" class="col-sm-9 h5"><?= $admin->getEmail(); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header align-items-center">
                                    <h5 class="modal-title" id="changePasswordModalLabel">Şifre Değiştir</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="changePasswordForm" method="POST" action="/api/adminprofile/changepassword">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="oldPassword" class="col-form-label">Mevcut Şifre</label>
                                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                                            <div class="valid-feedback">Geçerli.</div>
                                            <div class="invalid-feedback">Mevcut Şifre Yanlış</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword" class="col-form-label">Yeni Şifre</label>
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                            <div class="valid-feedback">Geçerli.</div>
                                            <div class="invalid-feedback">En az 5, en fazla 20 karakter olmalı
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmNewPassword" class="col-form-label">Yeni Şifreyi
                                               Onayla</label>
                                            <input type="password" class="form-control" id="confirmNewPassword" required>
                                            <div class="valid-feedback">Yeni şifre doğru</div>
                                            <div class="invalid-feedback">Şifreler Uyuşmuyor!</div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">İptal</button>
                                        <button id="changePasswordButton" type="submit" name="submit" class="btn btn-primary">Onayla</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
    </main>

    <?php include_once '../src/sablonlar/GenelScript.php' ?>
    <script type='text/javascript' src='/js/Admin/addLeftNav.js'></script>
    <script type='module' src="/js/Admin/AdminProfilim.js"></script>
</body>

</html>