<?php

use PHPMailer\PHPMailer\PHPMailer;
include_once '../src/Domain/Database.php';
include_once '../src/Domain/LoginPage/GeneralLoginFx.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$conn = $db->getConnection();

if(isset($_POST["submit"])){

    $email = $_POST["email"];

    require_once '../libs/PHPMailer/src/PHPMailer.php';
    require_once '../libs/PHPMailer/src/SMTP.php';
    require_once '../libs/PHPMailer/src/Exception.php';
   
    if(emailExists($conn,$email) == false){
        header("location: /login?fgemailnotExists");
        exit();
    }else{
        if(adminApproved($conn,$email) == false){

            if (checkVerification($conn,$email) == false) {
                header("location: /login?verify");
                exit();
            }

            header("location: /login?NotApprovedYet");
            exit();
        }

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
        $mail->SetFrom('no-reply@alumniSystem.com', 'Alumni System Admin');
        $mail->AddReplyTo('no-reply@alumniSystem.com', 'Alumni System Admin');
        $mail->AddAddress($email);
        $mail->Subject = 'Change Password';
        $content = str_replace(
            array('%password%', '%to%'),
            array($newPassword, $email),
            file_get_contents('../src/Domain/LoginPage/ForgotPasswordEmail.html')
        );
        $mail->msgHTML(file_get_contents('../src/Domain/LoginPage/ForgotPasswordEmail.html'), __DIR__);
        $mail->msgHTML($content, dirname(__FILE__));
        $mail->AltBody = 'A test email $newPassword';


        if ($mail->send()) {
            $status = 'success';
            $response = 'Email is sent!';
            header("location: /login?sendPsw");
            exit();
        }else{
            $status = 'failed';
            $response = 'error==='. $mail->ErrorInfo;
        }

        exit(json_encode(array("status" => $status,"response" => $response)));

    }
}

function checkVerification($conn,$email)
{
    $stmt = $conn->prepare('SELECT * FROM mezun WHERE email=:email AND mailOnay=1');
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $data = $stmt->fetchAll();
    if(!$data){
        return false;
    }else{
        return true;
    }
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
    $stmt = $conn->prepare("UPDATE mezun SET password=:password WHERE email=:email");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam("password", $newPassword);
    $stmt->execute();
}