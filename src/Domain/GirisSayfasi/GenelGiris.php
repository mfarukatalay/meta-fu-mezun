<?php


function Encrypt($email)
{
    $ciphering = "AES-128-CTR";

    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    $encryption_iv = '1234567891011121';

    $encryption_key = "ASTeam2";

    $encryption = openssl_encrypt(
        $email,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    return base64_encode($encryption);
}


function Decrypt($encryption)
{
    $ciphering = "AES-128-CTR";

    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $decryption_iv = '1234567891011121';

    $decryption_key = "ASTeam2";

    $decryption = openssl_decrypt(
        base64_decode($encryption),
        $ciphering,
        $decryption_key,
        $options,
        $decryption_iv
    );

    return $decryption;
}


function emailExists($conn, $email)
{

    $stmt = $conn->prepare("SELECT * FROM mezun WHERE email=? AND hesapAktifligi=1");
    $stmt->execute(array($email));

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['email'] === $email) {
            return $row;
        }
    }
    return false;
}


function adminApproved($conn,$email)
{

    $stmt = $conn->prepare('SELECT * FROM mezun WHERE email=:email AND onaylayan!=""');
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $data = $stmt->fetchAll();

    if(!$data){
        return false;
    }else{
        return true;
    }
}
