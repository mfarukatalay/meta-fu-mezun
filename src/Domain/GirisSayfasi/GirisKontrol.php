<?php

include_once '../src/Domain/Database.php';
include_once '../src/Domain/GirisSayfasi/GenelGiris.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$conn = $db->getConnection();
if(isset($_POST["submit"])){

    $email = $_POST["email"];
    $password = $_POST["password"];

    loginUser($conn, $email, $password);
    
}


function loginUser($conn, $email, $password)
{
    $alumniData = emailExists($conn,$email);
    if($alumniData == false){
        header("location: /login?emailnotExists");
        exit();
    }
    if(adminApproved($conn,$email) == false){
        header("location: /login?NotApprovedYet");
        exit();
    }
    

    $passwordHashed = $alumniData["password"];
    $checkpassword = password_verify($password, $passwordHashed);
    if ($checkpassword === false) {
        $_SESSION["CorrectEmail"] = $email;
        header("location: /login?passwordWrong");
        exit();
    } else if($checkpassword === true){
            unset($alumniData["password"]);
            unset($alumniData["tcNo"]);
            session_start();
            $_SESSION["mezun"] = $alumniData;
            unset($_SESSION["admin"]);
            header("location: /home");
            exit();
    }
}

function passwordCheck($password, $passwordNormal)
{
    if ($password == $passwordNormal) {
        return true;
    }elseif ($password != $passwordNormal) {
        header("location: /login?password=false");
        return false;
    }
}
