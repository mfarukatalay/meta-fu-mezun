<?php

function uploadImage($connection, $image, $imageId)
{
    $imageType = $image["fotoTip"];
    $blob = file_get_contents($image["tmp_name"]);

    try {
        $sql = "REPLACE INTO foto(fotoId,fotoTip,fotoVeri) VALUES(:fotoId,:fotoTip,:blob)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':fotoId', $imageId);
        $stmt->bindParam(':fotoTip', $imageType);
        $stmt->bindParam(':blob', $blob);
        
        return $stmt->execute();
    } catch (Exception $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
}

?>