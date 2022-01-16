<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once '../src/Domain/Profilim/ProfilModeli.php';
include_once '../src/Domain/AdminMezunYonetimi/AdminMezunYonetimModeli.php';
include_once '../src/Domain/Database.php';


if (isset($_POST['mezunId'])) {
  $alumniId = $_POST['mezunId'];
  $adminId = $_SESSION['admin']['adminId'];
  try {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $updateApprove = new  UpdateAlumniModel($db->getConnection());
    $updateApprove->updateApprovedby($adminId, $alumniId);
    $alumni = new MyProfile($db->getConnection(), $alumniId);
    sendApprovalEmail($alumni->getEmail());
    echo json_encode($updateApprove);
  } catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    include_once '../src/sablonlar/Baslik.php';
    include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
    exit();
  }
}

function sendApprovalEmail($email)
{

  $base_url = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/login";

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

  //email settings
  $mail->isHTML(true);
  $mail->SetFrom('mezun@mezunfu.com', 'Mezun Sistemi Admin');
  $mail->AddReplyTo('mezun@mezunfu.com', 'Mezun Sistemi Admin');
  $mail->AddAddress($email);
  $mail->Subject = 'Hesabınız Onaylandı';
  $content = str_replace(
    array('%url%', '%to%'),
    array($base_url, $email),
    file_get_contents('../src/Domain/AdminMezunYonetimi/AdminMailOnay.html')
  );
  $mail->msgHTML(file_get_contents('../src/Domain/AdminMezunYonetimi/AdminMailOnay.html'), __DIR__);
  $mail->msgHTML($content, dirname(__FILE__));
  $mail->AltBody = 'A test email $newPassword';

  if ($mail->send()) {
    $status = 'başarılı';
    $response = 'E-posta gönderildi!';
  } else {
    $status = 'failed';
    $response = 'error===' . $mail->ErrorInfo;
  }
}
