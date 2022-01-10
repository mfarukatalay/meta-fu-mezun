<?php
include_once '../src/Domain/Database.php';
include '../src/Domain/Profilim/ProfilModeli.php';
include '../src/araclar/FotoYukleme.php';

if (
    !isset($_POST['submit']) || !isset($_POST['bio'])
) {
    header("Location: /myprofile/edit");
    return;
}

$biography = $_POST['bio'];
$errorExist = false;
$errors = array();

if (strlen($biography) == 0) {
    array_push($errors, 'Biography cannot be empty!');
    $errorExist = true;
}

if ($errorExist == true) {
    return header("Location: /myprofile/edit?error[]=$errors[0]" . ($errorExist[1] ? "&error[]=$errors[0]" : ""));
}

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    if ($_FILES["profilePicture"]['tmp_name'] != null) {
        uploadImage($db->getConnection(), $_FILES["profilePicture"], $_SESSION["mezun"]['mezunId']);
    }
    $alumni = new MyProfile($db->getConnection(), $_SESSION["mezun"]['mezunId']);
    $alumni->setUpdatedData($biography);
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    include_once '../src/templates/header.php';
    include_once '../src/Domain/General_Pages/server_error.php';
    exit();
}

header("Location: /myprofile?updated=true");
