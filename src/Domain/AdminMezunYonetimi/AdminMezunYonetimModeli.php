<?php

class mezunListModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM mezun WHERE hesapAktifligi = 1 AND mailOnay =1
            ');
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

    public function getProfilePicture(): array{
        try {
            $stmt = $this->connection->prepare('
                SELECT * FROM mezun
                LEFT JOIN foto 
                ON mezun.fotoId=foto.fotoId WHERE hesapAktifligi=1 AND mailOnay =1');
            
            $stmt->execute();
            $data = $stmt->fetchAll();
            $image = array();
            foreach($data as $eachuser){
                if($eachuser['fotoId']==null){
                    array_push($image,null);
                }
                else if($eachuser['fotoVeri']){
                $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
                array_push($image,$temp_string);
                }
            }
            return $image;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
    }

    public function getNumberOfApprovedmezun(): int{
        try {
            $sql ='SELECT COUNT(mezunId) FROM mezun WHERE onaylayan!="" AND hesapAktifligi=1 AND mailOnay =1';
            $result = $this->connection->prepare($sql); 
            $result->execute(); 
            $number_of_rows = $result->fetchColumn(); 
            return $number_of_rows;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
    }

    public function getNumberOfUnapprovedmezun(): int{
        try {
            $sql ='SELECT COUNT(mezunId) FROM mezun WHERE onaylayan="" AND hesapAktifligi=1 AND mailOnay =1';
            $result = $this->connection->prepare($sql); 
            $result->execute(); 
            $number_of_rows = $result->fetchColumn(); 
            return $number_of_rows;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
    }

    public function search($name, $department, $status){
        if($status=="Approved"){
            $query = "
            SELECT * FROM `mezun` WHERE (isim LIKE '%$name%' AND bolum LIKE '%$department%') AND onaylayan!='' AND hesapAktifligi=1 AND mailOnay=1
            ";  
        }else if($status == "All"){
            $query = "
            SELECT * FROM `mezun` WHERE (isim LIKE '%$name%' AND bolum LIKE '%$department%') AND hesapAktifligi=1 AND mailOnay=1
            ";   
        }else if($status=="Pending Approval"){
            $query = "
            SELECT * FROM `mezun` WHERE (isim LIKE '%$name%' AND bolum LIKE '%$department%') AND onaylayan='' AND hesapAktifligi=1 AND mailOnay=1
            ";  
        }
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(); 
            $data = $stmt->fetchAll();
            if(!$data){
                return array();
            }
            return $data;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
    }

   
public function getSearch($mezunId) {
    try {
        $stmt = $this->connection->prepare("
        SELECT * FROM mezun LEFT JOIN foto ON mezun.fotoId=foto.fotoId WHERE mezunId='$mezunId' AND hesapAktifligi=1 AND mailOnay=1
        ");
        $stmt->execute();
        $data = $stmt->fetch();
        if($data['fotoId']=='Default'||$data['fotoId']==null){
            return '/Assets/imgs/default_user.png';
        }
        else if($data['fotoVeri']!=null){
            return 'data::'. $data['fotoTip'].';base64,'.base64_encode($data['fotoVeri']);
        }
    } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
}
    public function ismezunExist($mezunId){
        try {
            $stmt = $this->connection->prepare("
            SELECT count(*) FROM mezun WHERE mezunId =?
            ");
            $stmt->execute([$mezunId]);
            $data = $stmt->fetchAll();
            return $data;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
        }

        public function getmezun($mezunId)
        {
            try {
                $stmt = $this->connection->prepare('SELECT * FROM mezun WHERE mezunId=?');
                $stmt->execute([$mezunId]);
                $data = $stmt->fetchAll();
                if (!$data) {
                    include_once '../src/araclar/Degisken.php' ;
                    includeWithVariables('../src/sablonlar/Baslik.php');
                        include_once '../src/Domain/Genel_Sayfa/admin_sayfasi_bulunamadi.php';
                        include_once '../src/sablonlar/GenelScript.php';;     
                        exit;                     
                }
                return $data;
    
            } catch (PDOException $exception) {
                error_log('ActivityModel: getAll: ' . $exception->getMessage());
                throw $exception;
            }
            
       }

    

}

class DeletemezunModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('SELECT * FROM mezun WHERE hesapAktifligi = 1 AND mailOnay =1');
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

    public function getProfilePicture(): array{
        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM mezun
            LEFT JOIN foto 
            ON mezun.fotoId=foto.fotoId WHERE hesapAktifligi=1 AND mailOnay =1');
            
            $stmt->execute();
            $data = $stmt->fetchAll();
            $image = array();
            foreach($data as $eachuser){
                if($eachuser['fotoId']==null){
                    array_push($image,null);
                }
                else if($eachuser['fotoVeri']){
                    $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
                    array_push($image,$temp_string);
                }
            }
            return $image;
        } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
        }
    }

    public function deletemezun($mezunId) {
            try{
             $sql = "UPDATE mezun SET hesapAktifligi = 0 WHERE mezunId=?";
             $stmt = $this->connection->prepare($sql); 
             $stmt->execute([$mezunId]);
            }catch (PDOException $exception) {
                error_log('DeletemezunModel: construct: ' . $exception->getMessage());
                throw $exception;
            }
    }
}

class UpdatemezunModel
{
  private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM mezun WHERE hesapAktifligi = 1 AND mailOnay =1');
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

    public function getProfilePicture(): array{
        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM mezun
            LEFT JOIN foto 
            ON mezun.fotoId=foto.fotoId WHERE hesapAktifligi=1 AND mailOnay =1');
            $stmt->execute();
            $data = $stmt->fetchAll();
            $image = array();
            foreach($data as $eachuser){
                if($eachuser['fotoId']==null){
                    array_push($image,null);
            }
            else if($eachuser['fotoVeri']){
                $temp_string = 'data::' . $eachuser['fotoTip']. ';base64,'.base64_encode($eachuser['fotoVeri']);
                array_push($image,$temp_string);
            }
        }
        return $image;
    } catch (PDOException $e) {
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
    }
    }
    
    public function updatemezun($prevmezunId,$name,$gender,$department,$icNumber,$graduated,$biography,$imageId) {
            try{
             $sql = "UPDATE mezun SET isim=?,cinsiyet=?,tcNo=?,mznYil=?,bolum=?,bio=?, fotoIdId=? WHERE mezunId=?";
             $stmt = $this->connection->prepare($sql); 
             $stmt->execute([$name,$gender,$icNumber,$graduated,$department,$biography,$imageId,$prevmezunId]);
            }catch (PDOException $exception) {
                error_log('UpdatemezunModel: construct: ' . $exception->getMessage());
                throw $exception;
            }
    }

    public function updateApprovedby($adminId,$mezunId) {
        try{
         $sql = "UPDATE mezun SET onaylayan=? WHERE mezunId=?";
         $stmt = $this->connection->prepare($sql);  
         $stmt->execute([$adminId,$mezunId]);
        }catch (PDOException $exception) {
            error_log('UpdateApprovedByModel: construct: ' . $exception->getMessage());
            throw $exception;
        }       
} 
}
