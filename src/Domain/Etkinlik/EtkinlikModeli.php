<?php

declare(strict_types=1);
class EventModel
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
      SELECT * FROM etkinlik
      LEFT JOIN foto 
      ON etkinlik.fotoId=foto.fotoId;');
      $stmt->execute();
      $data = $stmt->fetchAll();

      if (!$data) {
        include_once '../src/Domain/Genel_Sayfa/sayfa_bulunamadi.php';
        include_once '../src/sablonlar/AltBilgi.php';
        include_once '../src/sablonlar/GenelScript.php';
        exit();
      }
      usort($data, fn ($a, $b) => strtotime($a['tarihSaat']) - strtotime($b['tarihSaat']));
      $data = array_reverse($data);
      return $data;
    } catch (PDOException $exception) {
      error_log('EventModel: getAll: ' . $exception->getMessage());
      throw $exception;
    }
  }

  public function getEvent(string $id): array
  {
    try {
      $stmt = $this->connection->prepare('
      SELECT * FROM etkinlik 
      LEFT JOIN foto 
      ON etkinlik.fotoId=foto.fotoId 
      WHERE etkinlikId= ?');
      $stmt->execute([$id]);
      $data = $stmt->fetch();
      $this->event = $data;
      if (!$data) {
        $GLOBALS['aciklama'] = TITLE_NOT_FOUND;
        http_response_code(404);
        include '../src/araclar/Degisken.php';
        includeWithVariables('../src/sablonlar/Baslik.php', array(
          'index' => '/css/Alumni/index.css'
        ));
        include '../src/sablonlar/nav.php';
        include '../src/Domain/Genel_Sayfa/sayfa_bulunamadi.php';
        include_once '../src/sablonlar/AltBilgi.php';
        include_once '../src/sablonlar/GenelScript.php';
        exit();
      }
      return $data;
    } catch (PDOException $exception) {
      error_log('EventModel: getEvent: ' . $exception->getMessage() . ' id: ' . $id);
      throw $exception;
    }
  }
  public function getEventPicture() // bu methot getEvent() çağrıldıktan sonra çağırılmalıdır
  {
    //handle if image is missing in database
    if (!$this->event['fotoTip'] || !$this->event['fotoVeri']) {
      return './Assets/imgs/default_events.jpg';
    }
    return 'data::' . $this->event['fotoTip'] . ';base64,' . base64_encode($this->event['fotoVeri']);
  }
  public function get6LatestEvent(): array // ana sayfanın kullanması için
  {
    try {
      $stmt = $this->connection->prepare('
      SELECT * FROM etkinlik');
      $stmt->execute();
      $data = $stmt->fetchAll();
      if (!$data) {
        return array();
      }
      // tarih saatine göre sırala
      usort($data, fn ($a, $b) => strtotime($a['tarihSaat']) - strtotime($b['tarihSaat']));
      $data = array_reverse(array_slice($data, -6, 6));
      return $data;
    } catch (PDOException $exception) {
      error_log('EventModel: get6LatestEvent: ' . $exception->getMessage());
      throw $exception;
    }
  }
  
  public function getEvents(string $alumniId): array 
  {
    try {
      $stmt = $this->connection->prepare('
      SELECT * FROM etkinlik
      LEFT JOIN foto 
      ON etkinlik.fotoId=foto.fotoId
      LEFT JOIN mezun_etkinlik 
      ON mezun_etkinlik.etkinlikId=etkinlik.etkinlikId
      WHERE mezunId=?;
      ');
      $stmt->execute([$alumniId]);
      $data = $stmt->fetchAll();

      if (!$data) {
        return array();
      }
      usort($data, fn ($a, $b) => strtotime($a['tarihSaat']) - strtotime($b['tarihSaat']));
      $data = array_reverse($data);
      return $data;
    } catch (PDOException $exception) {
      error_log('EventModel: getEvents: ' . $exception->getMessage());
      throw $exception;
    }
  }
  public function searchEvents(string $alumniId, string $search, bool $isMyEvent): array
  {
    $queryAftertrim = trim($search);
    if (!$queryAftertrim) { 
      return !$isMyEvent ? $this->getAll() : $this->getEvents($alumniId);
    }
    try {
      if ($isMyEvent) {
        $query = "SELECT etkinlik.*, foto.fotoTip, foto.fotoVeri, mezun_etkinlik.mezunId, mezun_etkinlik.mznGorüntüleme, mezun_etkinlik.mznBildirim FROM etkinlik
                  LEFT JOIN foto 
                  ON etkinlik.fotoId=foto.fotoId
                  LEFT JOIN mezun_etkinlik 
                  ON mezun_etkinlik.eventId=etkinlik.etkinlikId
                  WHERE (mezunId=?)
                  AND (aciklama LIKE '%$search%'
                  OR tanim LIKE '%$search%'
                  OR adres LIKE '%$search%');
      ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$alumniId]);
      } else {
        $query = "SELECT etkinlik.*, foto.fotoTip, foto.fotoVeri FROM etkinlik
                  LEFT JOIN foto 
                  ON etkinlik.fotoId=foto.fotoId
                  WHERE (aciklama LIKE '%$search%'
                  OR tanim LIKE '%$search%'
                  OR adres LIKE '%$search%');
      ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
      }
      $data = $stmt->fetchAll();
      if (!$data) {
        return array();
      }
      usort($data, fn ($a, $b) => strtotime($a['tarihSaat']) - strtotime($b['tarihSaat']));
      $data = array_reverse($data);
      return $data;
    } catch (PDOException $exception) {
      error_log('EventModel: searchMyEvents: ' . $exception->getMessage());
      throw $exception;
    }
  }
  public function EventImages($eventId)
  {
    try {
      $stmt = $this->connection->prepare('SELECT * FROM etkinlik LEFT JOIN foto ON etkinlik.fotoId=foto.fotoId WHERE etkinlikId=:etkinlikId');
      $stmt->bindParam(':etkinlikId', $eventId);
      $stmt->execute();
      $data = $stmt->fetchAll();
      $image = array();
      foreach ($data as $eachuser) {
        if (!is_null($eachuser['fotoVeri'])) {
          $temp_string = 'data::' . $eachuser['fotoTip'] . ';base64,' . base64_encode($eachuser['fotoVeri']);
          array_push($image, $temp_string);
        } else {
          $temp_path = './Assets/imgs/default_events.jpg';
          array_push($image, $temp_path);
        }
      }
      return $image;
    } catch (PDOException $exception) {
      error_log('EventModel: EventImages: ' . $exception->getMessage());
      throw $exception;
    }
  }
}
