<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/AdminProfil/AdminModeli.php';
include '../src/araclar/FotoYukleme.php';

if (
    !isset($_POST['submit']) || !isset($_POST['username']) 
) {
    header("Location: /adminprofile/edit");
    return;
}

$username = $_POST['username'];
$errorExist = false;
$errors = array();

if (strlen($username) < 5) {
    array_push($errors, 'Kullanıcı adı en az 5 karakter içermelidir');
    $errorExist = true;
}

if ($errorExist == true) {
    return header("Location: /myprofile/edit?error[]=$errors[0]" . ($errorExist[1] ? "&error[]=$errors[0]" : ""));
}

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    $admin = new AdminMyProfile($db->getConnection(), $_SESSION['admin']['adminId']);
    $admin->setUpdatedData($username);
    if($_FILES["profilePicture"]['tmp_name']!=null){
        uploadImage($db->getConnection(),$_FILES["profilePicture"],$admin->getAdminId());
    }
} catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
}

header("Location: /adminprofile?updated=true");
