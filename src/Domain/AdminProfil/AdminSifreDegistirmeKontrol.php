<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/AdminProfil/AdminModeli.php';

if(isset($_POST['submit']) && isset($_POST['newPassword']) && isset($_POST['oldPassword'])){
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    
    try{
        $admin = new AdminMyProfile($db->getConnection(), $_SESSION['admin']['adminId']);
        if(password_verify($_POST['oldPassword'],$admin->getPassword())){
            $admin->changePassword($_POST['newPassword']);
            return header("Location: /adminprofile?changepassword=success");
        }
    }catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
}

header("Location: /adminprofile?changepassword=fail");


