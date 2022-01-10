<?php


class EditMyJobModel
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

        if(!$data){
            return array();
        }
        return $data;     
    }

    public function editJob($jobId,$alumniId,$title,$description,$salary,$email,$postedDateTime,$imageId,$company,$location){
        $sql = "UPDATE isIlani SET isIlaniId=?, aciklama=?, mezunId=?, tanim=?, maas=?, email=?, gonderilenTarihSaat=?, fotoId=?, sirket=?, adres=? WHERE isIlanid=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$jobId,$title,$alumniId,$description,$salary,$email,$postedDateTime,$imageId,$company,$location,$jobId]);
    }

    public function getJobImage($id): array{
        $stmt = $this->connection->prepare("
            SELECT * FROM isIlani 
            LEFT JOIN image 
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