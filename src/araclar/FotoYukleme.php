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
include_once '../src/templates/header.php';
include_once '../src/Domain/General_Pages/server_error.php';
exit();
    }
}
?>

