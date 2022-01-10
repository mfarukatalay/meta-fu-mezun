<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/Profilim/ProfilModeli.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    $alumni = new MyProfile($db->getConnection(), $_SESSION["mezun"]['mezunId']);
    if (isset($_POST['private'])) {
        $alumni->setIsEmailPublic(0);
        header("Location: /myprofile?private=true");
    } else {
        $alumni->setIsEmailPublic(1);
        header("Location: /myprofile?private=false");
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    include_once '../src/sablonlar/Baslik.php';
    include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
    exit();
}
