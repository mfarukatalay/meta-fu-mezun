<?php

class AdminMyProfile
{

    private PDO $connection;
    private $id;
    private $user;

    public function __construct(PDO $connection, $id)
    {
        $this->connection = $connection;
        $this->id = $id;

        try {
            $stmt = $this->connection->prepare('
            SELECT * FROM admin 
            LEFT JOIN foto 
            ON admin.fotoId=foto.fotoId 
            WHERE adminId=:id');
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            $data = $stmt->fetch();
            $this->user = $data;
            if (!$data) {
                return array();
            }
            return $data;
        } catch (PDOException $exception) {
            error_log('MyProfileModel: construct: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function getAdminId()
    {
        return $this->user['adminId'];
    }

    public function getPassword()
    {
        return $this->user['password'];
    }

    public function getProfilePicture()
    {
        if(!$this->user['fotoTip'] || !$this->user['fotoVeri']){
            return '/Assets/imgs/default_user.png';
        }
        return 'data::'.$this->user['fotoTip'].';base64,'.base64_encode($this->user['fotoVeri']);
    }

    public function getName()
    {
        return $this->user['isim'];
    }

    public function getEmail()
    {
        return $this->user['email'];
    }

    public function setUpdatedData($name)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE admin SET isim=:isim WHERE adminId=:adminId');
            $stmt->bindParam(':isim', $name);
            $stmt->bindParam(':adminId', $this->id);
            $stmt->execute();
            $_SESSION['admin']['isim'] = $name;
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Update Data: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function changePassword($newPassword){
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        try {
            $stmt = $this->connection->prepare('UPDATE admin SET password=:password WHERE adminId=:adminId');
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':adminId', $this->id);
            $stmt->execute();
        } catch (PDOException $exception) {
            error_log('MyProfileModel: Change Password: ' . $exception->getMessage());
            throw $exception;
        }
    }
}
