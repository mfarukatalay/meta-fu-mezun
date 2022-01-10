<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once '../src/Domain/Database.php';
include_once '../src/Domain/GirisSayfasi/GenelGiris.php';
include_once '../src/araclar/FotoYukleme.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$conn = $db->getConnection();

if (isset($_POST["submit"])) {
    $firstname = $_POST["FirstNameID"];
    $lastname = $_POST["LastNameID"];
    $name = $firstname . ' ' . $lastname;
    $gender = $_POST["cinsiyet"];
    $Batch = $_POST["Batch"];
    $email = $_POST["email"];
    $IC = $_POST["tcNo"];
    $department = $_POST["bolum"];
    $Password = $_POST["Password"];
    $alumniId = "";
    $approvedBy = "";
    $imageId = "";
    $isEmailPublic = False;
    $isActive = True;
    $isVerified = False;
    $biography = "";
    $pic = "profilePicture";

    insertAlumni($conn, $alumniId, $approvedBy, $email, $Password, $IC, $gender, $name, $department, $Batch, $imageId, $isEmailPublic, $isActive, $isVerified, $biography);

    header("location: /login?doneSend");
    exit();
}


function insertAlumni($conn, $alumniId, $approvedBy, $email, $Password, $IC, $gender, $name, $department, $Batch, $imageId, $isEmailPublic, $isActive, $isVerified, $biography)
{
    $stmt = $conn->prepare("INSERT INTO mezun (mezunId, onaylayan, email, password, tcNo, cinsiyet, isim, bolum, mznYil, fotoId, emailGizlilik, hesapAktifligi, mailOnay, bio) VALUES(:mezunId, :onaylayan, :email, :password, :tcNo, :cinsiyet, :isim, :bolum, :mznYil, :fotoId, :emailGizlilik, :hesapAktifligi, :mailOnay, :bio)");

    $checkEmail = emailExists($conn, $email);
    if ($checkEmail) {
        header("location: /login?emailExists");
        exit();
    }

    $encrypted = Encrypt($email);
    verifyEmail($email, $encrypted);

    $alumniId = "AL-" . (getLength($conn) + 1);
    $imageId = $alumniId;

    $stmt->bindParam(":mezunId", $alumniId);
    $stmt->bindParam(":onaylayan", $approvedBy);
    $stmt->bindParam(":email", $email);

    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    $stmt->bindParam(":password", $hashedPassword);
    $stmt->bindParam(":tcNo", $IC);
    $stmt->bindParam(":cinsiyet", $gender);
    $stmt->bindParam(":isim", $name);
    $stmt->bindParam(":bolum", $department);
    $stmt->bindParam(":mznYil", $Batch);
    $stmt->bindParam(":fotoId", $imageId);
    $stmt->bindParam(":emailGizlilik", $isEmailPublic);
    $stmt->bindParam(":hesapAktifligi", $isActive);
    $stmt->bindParam(":mailOnay", $isVerified);
    $stmt->bindParam(":bio", $biography);
    $stmt->execute();

    try {
        if ($_FILES["profilePicture"]['tmp_name'] != null) {
            uploadImage($conn, $_FILES["profilePicture"], $alumniId);
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        include_once '../src/sablonlar/Baslik.php';
        include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
        exit();
    }
}


function getLength($conn)
{
    $stmt = $conn->prepare("SELECT max( CONVERT ( substring_index(mezunId,'-',-1), UNSIGNED ) ) AS max FROM mezun");
    $stmt->execute();
    $data = $stmt->fetch();
    return $data["max"];
}


function verifyEmail($email, $encrypted)
{

    $base_url = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/login?id=" . $encrypted;

    require_once '../libs/PHPMailer/src/PHPMailer.php';
    require_once '../libs/PHPMailer/src/SMTP.php';
    require_once '../libs/PHPMailer/src/Exception.php';

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = SYSTEM_EMAIL_USERNAME;
    $mail->Password = SYSTEM_EMAIL_PASSWORD;
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    $mail->isHTML(true);
    $mail->SetFrom('no-reply@alumniSystem.com', 'Alumni System Admin');
    $mail->AddReplyTo('no-reply@alumniSystem.com', 'Alumni System Admin');
    $mail->AddAddress($email);
    $mail->Subject = 'Verify Your Email';
    $content = str_replace(
        array('%url%', '%to%'),
        array($base_url, $email),
        file_get_contents('../src/Domain/GirisSayfasi/MailOnay.html')
    );
    $mail->msgHTML(file_get_contents('../src/Domain/GirisSayfasi/MailOnay.html'), __DIR__);
    $mail->msgHTML($content, dirname(__FILE__));
    $mail->AltBody = 'A test email $newPassword';

    if ($mail->send()) {
        $status = 'success';
        $response = 'Email is sent!';
    } else {
        $status = 'failed';
        $response = 'error===' . $mail->ErrorInfo;
    }
}
