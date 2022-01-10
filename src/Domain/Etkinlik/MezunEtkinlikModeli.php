<?php

class AlumniEventModel
{
  private PDO $connection;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function getAll(): array
  {
    try {
      $query = 'SELECT * FROM mezun_etkinlik';
      $stmt = $this->connection->prepare($query);
      $stmt->execute();
      $data = $stmt->fetchAll();

      if (!$data) {
        return array();
      }
      return $data;
    } catch (PDOException $exception) {
      error_log('AlumniEventModel: getAll: ' . $exception->getMessage());
      throw $exception;
    }
  }

  public function getAllEventInfoByAlumniId(string $id): array
  {
    try {
      $query =
        'SELECT mezun_etkinlik.tarihSaat, mezun_etkinlik.mznGorüntüleme, mezun_etkinlik.mznBildirim, etkinlik.etkinlikId, mezun_etkinlik.mezunId, etkinlik.adminId, etkinlik.aciklama, etkinlik.tanim, etkinlik.fotoId, etkinlik.adres FROM mezun_etkinlik 
       RIGHT JOIN etkinlik
       ON mezun_etkinlik.etkinlikId = etkinlik.etkinlikId 
       WHERE mezunId = ?';
      $stmt = $this->connection->prepare($query);
      $stmt->execute([$id]);
      $data = $stmt->fetchAll();

      if (!$data) {
        return array();
      }
      foreach ($data as &$alumni_event) {
        $temp = new DateTime($alumni_event['tarihSaat']);
        $pastDateTimeSecond = (int)($temp->format("U"));
        $curMilliSeconds = (microtime(true));
        $secondSinceInvitation = (int)round(($curMilliSeconds - $pastDateTimeSecond));
        $minute = (int)floor($secondSinceInvitation / 60);
        $hour = (int)floor($minute / 60);
        $day = (int)floor($hour / 24);
        if ($day === 0) {
          if ($hour % 60 === 0) {
            $timeStr = "$minute minute(s) ago";
          } else {
            $timeStr = "$hour hour(s) ago";
          }
        } else {
          $timeStr = "$day day(s) ago";
        }
        $alumni_event['timeStr'] = $timeStr;
      }
      return $data;
    } catch (PDOException $exception) {
      error_log('AlumniEventModel: getByAlumniId: ' . $exception->getMessage() . ' mezunId: ' . $id);
      throw $exception;
    }
  }
  public function setNotificationClosedTrue(string $eventId): array
  {
    try {
      $stmt = $this->connection->prepare('
      UPDATE mezun_etkinlik 
      SET mznBildirim = 1 
      WHERE etkinlikId = ?;');
      $stmt->execute([$eventId]);
      $data = $stmt->fetch();

      if (!$data) {
        return array();
      }
      return $data;
    } catch (PDOException $exception) {
      error_log('AlumniEventModel: setNotificationClosedTrue: ' . $exception->getMessage() . ' etkinlikId: ' . $eventId);
      throw $exception;
    }
  }
  public function setViewedByAlumniTrue(string $eventId): array
  {
    try {
      $stmt = $this->connection->prepare('
      UPDATE mezun_etkinlik 
      SET mznGorüntüleme = 1 
      WHERE etkinlikId = ?');
      $stmt->execute([$eventId]);
      $data = $stmt->fetch();

      if (!$data) {
        return array();
      }
      return $data;
    } catch (PDOException $exception) {
      error_log('AlumniEventModel: setViewedByAlumniTrue: ' . $exception->getMessage() . ' etkinlikId: ' . $eventId);
      throw $exception;
    }
  }
}
