<?php

class MyProfile
{

    private PDO $connection;
    private $id;
    private $user;
    private $exist = false;

    public function __construct(PDO $connection, $id)
    {
        $this->connection = $connection;
        $this->id = $id;

        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM mezun 
            LEFT JOIN foto 
            ON mezun.fotoId=foto.fotoId 
            WHERE mezunId=:id AND hesapAktifligi=1');
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            $data = $stmt->fetch();
            $this->user = $data;
            if ($data) {
                $this->exist = true;
            }
            return $data;
        } catch (PDOException $exception) {
            error_log('MyProfileModel: construct: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function isAlumniExist(){
        return $this->exist;
    }

    public function getAlumniId()
    {
        return $this->user['mezunId'];
    }

    public function getPassword()
    {
        return $this->user['password'];
    }

    public function getProfilePicture()
    {
        try{

            if(!$this->user['fotoTip'] || !$this->user['fotoVeri']){
                return '/Assets/imgs/default_user.png';
            }
            return 'data::'.$this->user['fotoTip'].';base64,'.base64_encode($this->user['fotoVeri']);
        }catch(Exception $exception){
            error_log("Exception: " . $exception->getMessage());
        }
    }

    public function getName()
    {
        return $this->user['isim'];
    }
    public function getGender()
    {
        return $this->user['cinsiyet'];
    }

    public function getGraduatedYear()
    {
        return $this->user['mznYil'];
    }

    public function getDepartment()
    {
        return $this->user['bolum'];
    }

    public function getEmail()
    {
        return $this->user['email'];
    }

    public function getBiography()
    {
        return $this->user['bio'];
    }

    public function getIsActive()
    {
        return $this->user['hesapAktifligi'];
    }
    public function getIsEmailPublic()
    {
        return $this->user['emailGizlilik'];
    }

    public function setIsEmailPublic($isEmailPublic)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE mezun SET emailGizlilik=:emailGizlilik WHERE mezunId=:mezunId');
            $stmt->bindParam(':emailGizlilik', $isEmailPublic);
            $stmt->bindParam(':mezunId', $this->id);
            $stmt->execute();
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Update Email Privacy: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function setUpdatedData($biography)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE mezun SET bio=:bio WHERE mezunId=:mezunId');
            $stmt->bindParam(':bio', trim($biography));
            $stmt->bindParam(':mezunId', $this->id);
            $stmt->execute();
            $_SESSION['mezun']['bio'] = $biography;
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Update Data: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function deleteAccount(){
        try {
            $stmt = $this->connection->prepare('UPDATE mezun SET hesapAktifligi=0 WHERE mezunId=:mezunId');
            $stmt->bindParam(':mezunId', $this->id);
            $stmt->execute();
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Delete Account: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function changePassword($newPassword){
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        try {
            $stmt = $this->connection->prepare('UPDATE mezun SET password=:password WHERE mezunId=:mezunId');
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':mezunId', $this->id);
            $stmt->execute();
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Change Password: ' . $exception->getMessage());
            throw $exception;
        }
    }
}
