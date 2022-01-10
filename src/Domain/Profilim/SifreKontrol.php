<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/Profilim/ProfilModeli.php';

if (isset($_POST['submit']) && isset($_POST['newPassword']) && isset($_POST['oldPassword'])) {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

    try {
        $alumni = new MyProfile($db->getConnection(), $_SESSION["mezun"]['mezunId']);
        if (password_verify($_POST['oldPassword'], $alumni->getPassword())) {
            $alumni->changePassword($_POST['newPassword']);
            return header("Location: /myprofile?changepassword=success");
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        include_once '../src/sablonlar/header.php';
        include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
        exit();
    }
}

header("Location: /myprofile?changepassword=fail");
