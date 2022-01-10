<?php

class JobModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM isIlani ORDER BY gonderilenTarihSaat DESC');
            $stmt->execute();
            $data = $stmt->fetchAll();
            if (!$data) {
                return array();
            }
            return $data;

        } catch (PDOException $exception) {
            error_log('ActivityModel: getAll: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function search($searchterm){
        $query = "SELECT * FROM `isIlani` WHERE (aciklama LIKE '%$searchterm%' OR tanim LIKE '%$searchterm%' OR maas LIKE '%$searchterm%' OR sirket LIKE '%$searchterm%'  OR adres LIKE '%$searchterm%') ORDER BY gonderilenTarihSaat DESC";  
        $stmt = $this->connection->prepare($query);  
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }

    public function getJobImage(): array{
        $stmt = $this->connection->prepare('
            SELECT * FROM isIlani 
            LEFT JOIN foto 
            ON isIlani.fotoId=foto.fotoId 
            ORDER BY gonderilenTarihSaat DESC');
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
    
    public function getSearch($id) {
        $stmt = $this->connection->prepare("
            SELECT * FROM isIlani
            LEFT JOIN foto 
            ON isIlani.fotoId=foto.fotoId 
            WHERE isIlaniId='$id' ");
        $stmt->execute();
        $data = $stmt->fetch();
        if(!is_null($data['fotoVeri'])){
            return 'data::'. $data['fotoTip'].';base64,'.base64_encode($data['fotoVeri']);
        }else{
            return '/Assets/imgs/jobdefault.jpg';
        }
       
    }

    public function Nicole(){
        $query = "SELECT * FROM isIlani ORDER BY gonderilenTarihSaat DESC LIMIT 4";  
        $stmt = $this->connection->prepare($query);  
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }


    public function NicoleImages(){
        $stmt = $this->connection->prepare('SELECT * FROM isIlani LEFT JOIN foto ON isIlani.fotoId=foto.fotoId ORDER BY gonderilenTarihSaat DESC LIMIT 4');
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