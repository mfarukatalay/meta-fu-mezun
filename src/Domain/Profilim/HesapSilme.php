<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/Profilim/ProfilModeli.php';

if (isset($_POST['submit'])) {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

    try {
        $alumni = new MyProfile($db->getConnection(), $_SESSION["mezun"]['mezunId']);
        if (password_verify($_POST['deletePassword'], $alumni->getPassword())) {
            $alumni->deleteAccount();
            session_destroy();
            header("Location: /login?delete=success");
            exit;
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        include_once '../src/sablonlar/Baslik.php';
        include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
        exit();
    }
}

header("Location: /myprofile?delete=fail");
