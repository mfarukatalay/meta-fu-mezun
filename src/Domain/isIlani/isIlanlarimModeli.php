<?php


class MyJobModel
{
  private PDO $connection;
  private $user;
  private $id;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getJobImage($id): array{
        $stmt = $this->connection->prepare('
            SELECT * FROM isIlani 
            LEFT JOIN foto 
            ON isIlani.fotoId=foto.fotoId 
            WHERE mezunId=:id ORDER BY gonderilenTarihSaat DESC');
        $stmt->bindParam(':id', $id);
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

    public function getRow($id): array{
        $stmt = $this->connection->prepare("SELECT * FROM isIlani WHERE mezunId='$id' ORDER BY gonderilenTarihSaat DESC");
        $stmt->execute();
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data;     

    }

    public function getNumRow($alumniID): int{
        $stmt = $this->connection->query("SELECT COUNT(*) FROM isIlani WHERE mezunId='$alumniID'")->fetchColumn();
        return $stmt;
    }

    public function deleteJob($myJobId) {
        $sql = 'DELETE FROM isIlani WHERE isIlaniId = :isIlaniId';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':isIlaniId', $myJobId);

        $stmt->execute();
    }

    public function deleteJobImage($myJobId) {
        $sql = 'DELETE FROM foto WHERE fotoId = :fotoId';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':fotoId', $myJobId);

        $stmt->execute();
    }

    public function search($searchterm,$alumniID) :array{
        $query = "SELECT * FROM `isIlani` WHERE mezunId='$alumniID' AND (aciklama LIKE '%$searchterm%' OR tanim LIKE '%$searchterm%' OR maas LIKE '%$searchterm%' OR sirket LIKE '%$searchterm%'  OR adres LIKE '%$searchterm%')  ORDER BY gonderilenTarihSaat DESC";  
        $stmt = $this->connection->prepare($query);  
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }

    public function getSearch($id) {
        $stmt = $this->connection->prepare("
            SELECT * FROM isIlani
            LEFT JOIN image 
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
    

}
