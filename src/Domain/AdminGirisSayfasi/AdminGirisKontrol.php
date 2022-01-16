<?php

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
    $password = $_POST["password"];

    loginUser($conn, $email, $password);

}



function loginUser($conn, $email, $password)
{

    $adminData = emailExists($conn,$email);
    if($adminData == false){
        header("location: /admin-login?emailnotExists");
        exit();
    }


    $passwordHashed = $adminData["password"];
    $checkpassword = password_verify($password, $passwordHashed);

    if ($checkpassword === false) {
       
        header("location: /admin-login?passwordWrong");
        exit();

    } else if($checkpassword === true){

            unset($adminData["password"]);
            unset($adminData["tcNo"]);
            session_start();
            $_SESSION["admin"] = $adminData;
            unset($_SESSION["mezun"]);
            header("location: /admin");
            exit();
        
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

function passwordCheck($password, $passwordNormal)
{
    if ($password == $passwordNormal) {
        return true;
    }elseif ($password != $passwordNormal) {
        header("location: /admin-login?password=false");
        return false;
    }
}


