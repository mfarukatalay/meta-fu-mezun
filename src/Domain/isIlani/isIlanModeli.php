<?php


class AddJobModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function addJobs($jobId,$alumniId,$title,$description,$salary,$email,$postedDateTime,$imageId,$company,$location) {
             $sql = "INSERT INTO isIlani (isIlaniId,aciklama,mezunId,tanim,maas,email,gonderilenTarihSaat,fotoId,sirket,adres) VALUES(:isIlaniId,:aciklama,:mezunId,:tanim,:maas,:email,:gonderilenTarihSaat,:fotoId,:sirket,:adres)";
             $stmt = $this->connection->prepare($sql);
             $result = $stmt->execute(array(':isIlaniId'=>$jobId,':aciklama'=>$title,'mezunId'=>$alumniId,':tanim'=>$description,'maas'=>$salary,':email'=>$email,':gonderilenTarihSaat'=>$postedDateTime,':fotoId'=>$imageId,':sirket'=>$company,':adres'=>$location));
    }
    
    public function getMaxId(): int{
        $stmt = $this->connection->query("SELECT max( CONVERT ( substring_index(isIlaniId,'-',-1), UNSIGNED ) ) AS max FROM isIlani")->fetchColumn();
        return (int)$stmt;

    }

}

?>