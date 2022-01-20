<?php

class Admin_EventModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM etkinlik');
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
   public function getEvent($eventId)
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM etkinlik WHERE etkinlikId=?');
            $stmt->execute([$eventId]);
            $data = $stmt->fetchAll();
            if (!$data) {
                include_once '../src/Domain/Genel_Sayfa/admin_sayfasi_bulunamadi.php';
                return exit;
            }
            return $data;
        } catch (PDOException $exception) {
            error_log('ActivityModel: getAll: ' . $exception->getMessage());
            throw $exception;
        }
        
   }
   public function deleteEvent($eventId) {
    $sql ="DELETE FROM mezun_etkinlik WHERE etkinlikId=?";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$eventId]);

    $sql = "DELETE FROM etkinlik WHERE etkinlikId=?";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$eventId]);
    
    $sql = "DELETE FROM foto WHERE fotoId=?";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([$eventId]);
    }

    public function getPicture(): array{
        $stmt = $this->connection->prepare('
            SELECT * FROM etkinlik
            LEFT JOIN foto 
            ON etkinlik.fotoId=foto.fotoId');
        $stmt->execute();
        $data = $stmt->fetchAll();
        $image = array();
        foreach($data as $eachuser){
            if($eachuser['fotoId']=='Default'||$eachuser['fotoId']==null){
                array_push($image,null);
            }
            else if($eachuser['fotoVeri']){
            $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
            array_push($image,$temp_string);
            }
        }
        return $image;
    }

    public function getSearch($id) {
        $stmt = $this->connection->prepare("
            SELECT * FROM etkinlik
            LEFT JOIN foto 
            ON etkinlik.fotoId=foto.fotoId 
            WHERE etkinlikId='$id' ");
        $stmt->execute();
        $data = $stmt->fetch();
        if($data['fotoId']=='Default'||$data['fotoId']==null){
            return '/Assets/imgs/default_events.jpg';
        }
        else if($data['fotoVeri']!=null){
            return 'data::'. $data['fotoTip'].';base64,'.base64_encode($data['fotoVeri']);
        }
    }
    public function search($searchterm){
        $query = "SELECT * FROM `etkinlik` WHERE (aciklama LIKE '%$searchterm%' OR tanim LIKE '%$searchterm%' OR adres LIKE '%$searchterm%') ";  
        $stmt = $this->connection->prepare($query); 
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }
    public function getNumberOfEvent(): int{
        $sql ="SELECT COUNT(etkinlikId) FROM etkinlik";
        $result = $this->connection->prepare($sql); 
        $result->execute(); 
        $number_of_rows = $result->fetchColumn(); 
        return $number_of_rows;
    }
}

class Admin_Alumni_EventModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM mezun_etkinlik');
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


}
class AlumniModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM mezun WHERE hesapAktifligi=1 AND mailOnay =1 AND onaylayan!=""');
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
    public function getPicture(): array{
        $stmt = $this->connection->prepare('
        SELECT * FROM mezun
        LEFT JOIN foto 
        ON mezun.fotoId=foto.fotoId WHERE hesapAktifligi=1 AND mailOnay =1 AND onaylayan!=""');
        $stmt->execute();
        $data = $stmt->fetchAll();
        $image = array();
        foreach($data as $eachuser){
            if($eachuser['fotoId']=='Default'||$eachuser['fotoId']==null){
                array_push($image,null);
            }
            else if($eachuser['fotoVeri']){
            $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
            array_push($image,$temp_string);
            }
        }
        return $image;
    }
    public function getSearch($id) {
        $stmt = $this->connection->prepare("
            SELECT * FROM mezun 
            LEFT JOIN foto 
            ON mezun.fotoId=foto.fotoId 
            WHERE mezunId='$id' AND (hesapAktifligi=1 AND mailOnay =1 AND onaylayan!='')");
        $stmt->execute();
        $data = $stmt->fetch();
        if($data['fotoId']=='Default'||$data['fotoId']==null){
            return '/Assets/imgs/default_user.jpg';
        }
        else if($data['fotoVeri']!=null){
            return 'data::'. $data['fotoTip'].';base64,'.base64_encode($data['fotoVeri']);
        }
    }
    public function search($searchterm){
        $query = "SELECT * FROM `mezun` WHERE (hesapAktifligi=1 AND mailOnay =1 AND onaylayan!='') AND (isim LIKE '%$searchterm%' OR bolum LIKE '%$searchterm%') "; 
         $stmt = $this->connection->prepare($query);  
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }
    public function searchDepartment($searchterm){
        $query = "SELECT * FROM `mezun` WHERE (hesapAktifligi=1 AND mailOnay =1 AND onaylayan!='') AND (bolum LIKE '%$searchterm%') ";  
        $stmt = $this->connection->prepare($query);  
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        if(!$data){
            return array();
        }
        return $data; 
    }
    public function searchStatus($status, $eventId){
        $query = "SELECT mezunId FROM `mezun_etkinlik` WHERE etkinlikId=?";  
        $stmt = $this->connection->prepare($query);  
        $stmt->execute([$eventId]);
        $data = $stmt->fetchAll();
        $alumni = array();
        foreach($data as $eachAlumni){
            $query = "SELECT * FROM `mezun` WHERE mezunId=? AND (hesapAktifligi=1 AND mailOnay =1 AND onaylayan!='') ";  
            $stmt = $this->connection->prepare($query);  
            $stmt->execute([$eachAlumni['mezunId']]);
            $alumniData = $stmt->fetch(PDO::FETCH_ASSOC);
            array_push($alumni,$alumniData);
        }
        if(!$data){
            return array();
        }
        if($status=='Invited'){
            return $alumni; 
        }else{
            $stmt = $this->connection->prepare('SELECT * FROM mezun WHERE hesapAktifligi=1 AND mailOnay =1 AND onaylayan!=""');
            $stmt->execute();
            $allAlumni = $stmt->fetchAll();
            foreach($alumni as $eachAlumni){
                if(in_array($eachAlumni,$allAlumni)){
                    $key=array_search($eachAlumni,$allAlumni);
                    unset($allAlumni[$key]);
                }
            }
            $allAlumni = array_values($allAlumni);
            return $allAlumni;
    }
    }
}

class UpdateEventModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function updateEvent($eventId,$adminId,$title,$newDate,$description,$imageId,$locate) {
             try{
             $sql = "UPDATE etkinlik SET adminId=?, aciklama=?,tarihSaat=?,tanim=?,fotoId=?,adres=? WHERE etkinlikId=?";
             $stmt = $this->connection->prepare($sql);  
             $stmt->execute([$adminId,$title,$newDate,$description,$imageId,$locate,$eventId]);
            }catch (PDOException $exception) {
                error_log('UpdateEventModel: construct: ' . $exception->getMessage());
                throw $exception;
            }     
    }
    public function getImageId($eventId) :string {
         $sql = "SELECT fotoId FROM `etkinlik` WHERE etkinlikId=?";
         $stmt = $this->connection->prepare($sql);  
         $stmt->execute([$eventId]);
         $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data['fotoId']!=null){
            // echo $data['imageId'];
            return $data['fotoId'];
        }
        else{
            // echo 'default';
            return "Default";
        }
}


}

class createEventModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function updateEvent($eventId,$adminId,$title,$newDate,$description,$imageId,$locate) {
             $sql = "INSERT INTO etkinlik (etkinlikId,adminId,aciklama,tarihSaat,tanim,fotoId,adres) VALUES(:etkinlikId,:adminId,:aciklama,:tarihSaat,:tanim,:fotoId,:adres)";
             $stmt = $this->connection->prepare($sql);
             $result = $stmt->execute(array(':etkinlikId'=>$eventId,':adminId'=>$adminId,':aciklama'=>$title,':tarihSaat'=>$newDate,':tanim'=>$description,'fotoId'=>$imageId,':adres'=>$locate));
    }
    public function getMaxId(): int{
        $stmt = $this->connection->query("SELECT max( CONVERT ( substring_index(etkinlikId,'-',-1), UNSIGNED ) ) AS max FROM etkinlik")->fetchColumn();
        return (int)$stmt;
    }
}

class InviteAlumniModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM mezun_etkinlik');
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
     public function InviteAlumni($alumniId,$eventId,$dateTime) {
        $sql ="SELECT * FROM mezun_etkinlik WHERE mezunId=? AND etkinlikId=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$alumniId,$eventId]);
        if($stmt->rowCount() == 0)
        {
            try{
                $sql ="INSERT INTO mezun_etkinlik (mezunId, etkinlikId, mznGorüntüleme, tarihSaat, mznBildirim)
                    VALUES (?,?,'false',?,'false')";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([$alumniId,$eventId,$dateTime]);
                }catch (PDOException $exception) {
                    error_log('InviteAlumniModel: construct: ' . $exception->getMessage());
                    throw $exception;
                }     
            }
        }
}
?>