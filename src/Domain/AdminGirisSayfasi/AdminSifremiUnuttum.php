<?php

use PHPMailer\PHPMailer\PHPMailer;
include_once '../src/Domain/Database.php';
try {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $conn = $db->getConnection();
  } catch (Exception $e) {
    // echo "Exception: " . $e->getMessage();
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }

if(isset($_POST["submit"])){

    $email = $_POST["email"];

    require_once '../libs/PHPMailer/src/PHPMailer.php';
    require_once '../libs/PHPMailer/src/SMTP.php';
    require_once '../libs/PHPMailer/src/Exception.php';

    if(emailExists($conn,$email) == false){
        header("location: /admin-login?fgemailnotExists");
        exit();
    }else{

        $newPassword = randomPassword();
        $hashednewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        setNewPassword($conn,$hashednewPassword,$email);

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = SYSTEM_EMAIL_USERNAME;
        $mail->Password = SYSTEM_EMAIL_PASSWORD;
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';


        $mail->isHTML(true);
        $mail->SetFrom('mezun@mezunfu.com', 'Mezun Sistemi Admin');
        $mail->AddReplyTo('mezun@mezunfu.com', 'Mezun Sistemi Admin');
        $mail->AddAddress($email);
        $mail->Subject = 'Şifre Değiştir';
        $content = str_replace(
            array('%password%', '%to%'),
            array($newPassword,    $email),
            file_get_contents('../src/Domain/GirisSayfasi/SifremiUnuttum.html')
        );
        $mail->msgHTML(file_get_contents('../src/Domain/GirisSayfasi/SifremiUnuttum.html'), __DIR__);
        $mail->msgHTML($content, dirname(__FILE__));
        $mail->AltBody = 'Bir test e-postası $newPassword';


        if ($mail->send()) {
            $status = 'başarılı';
            $response = 'E-posta gönderildi!';
            header("location: /admin-login?sendPsw");
            exit();
        }else{
            $status = 'failed';
            $response = 'error==='. $mail->ErrorInfo;
        }

        exit(json_encode(array("status" => $status,"response" => $response)));

    }
}

function emailExists($conn,$email)
{

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
    $stmt->execute(array($email));
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['email'] === $email) {
            return $row;
        }
    }
        return false;
}

function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); 
}

function setNewPassword($conn,$newPassword,$email)
{
    $stmt = $conn->prepare("UPDATE admin SET password=:password WHERE email=:email");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam("password", $newPassword);
    $stmt->execute();
}