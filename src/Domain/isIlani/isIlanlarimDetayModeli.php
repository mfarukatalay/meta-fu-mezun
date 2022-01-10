<?php

class MyJobDetailsModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getRow($id): array{
        $stmt = $this->connection->prepare("SELECT * FROM isIlani WHERE isIlaniId='$id'");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            include_once '../src/Domain/Genel_Sayfa/sayfa_bulunamadi.php';
            include_once '../src/sablonlar/AltBilgi.php';
            include_once '../src/sablonlar/GenelScript.php';
            exit;
        }
        return $data;     
}

public function getJobImage($id): array{
    $stmt = $this->connection->prepare("
        SELECT * FROM isIlani 
        LEFT JOIN foto 
        ON isIlani.fotoId=foto.fotoId 
        WHERE isIlaniId='$id'
        ORDER BY gonderilenTarihSaat DESC");
    $stmt->execute();
    $data = $stmt->fetchAll();
    $image = array();
    foreach($data as $eachuser){
        if(!is_null($eachuser['fotoVeri'])){
        $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
        array_push($image,$temp_string);
        }else{
            $temp_path = '/Assets/imgs/jobdefault.jpg';
            array_push($image,$temp_path);
        }
    }
    return $image;
}


}

?>