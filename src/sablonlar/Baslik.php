<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="/Assets/imgs/UM_Logo.ico" type="image/x-icon">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />

  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  
  <link rel="stylesheet" type="text/css" href="/css/Alumni/index.css" />
  <?php

  if (isset($variables)) {
    foreach ($variables as $value) {
      echo "<link rel='stylesheet' type='text/css' href='{$value}' />";
    }
  }
  ?>
  <title><?= $GLOBALS['aciklama']; ?></title>
</head>

<body>

  <?php
  include_once '../src/Domain/Database.php';
  include_once '../src/Domain/Etkinlik/EtkinlikModeli.php';
  include_once '../src/Domain/Etkinlik/MezunEtkinlikModeli.php';
  include_once '../src/Domain/Profilim/ProfilModeli.php';
  include_once '../src/Domain/Admin-MyProfile/AdminModel.php';
  ?>
  <?php
  function isAlumni()
  {
    return isset($_SESSION['mezun']) && !empty($_SESSION['mezun']);
  }
  try {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    if (isAlumni()) {
      $my_profile_model = new MyProfile($db->getConnection(), $_SESSION['mezun']['mezunId']);
      $profile_img_src = $my_profile_model->getProfilePicture();
      $event_model = new EventModel($db->getConnection());
      $alumni_event_model = new AlumniEventModel($db->getConnection());
      $all_events = $event_model->getAll();
      $all_alumni_events = $alumni_event_model->getAllEventInfoByAlumniId($_SESSION['mezun']['mezunId']);
      $server_error = false;
    } else {
      $admin_profile_model = new AdminMyProfile($db->getConnection(), $_SESSION['admin']['adminId']);
      $profile_img_src = $admin_profile_model->getProfilePicture();
      $server_error = false;
    }
  } catch (Exception $e) {
    $server_error = true;
error_log("Exception: " . $e->getMessage());
include_once '../src/sablonlar/Baslik.php';
include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
exit();
  }
  ?>
  <?php if (!$server_error) {
    if (isAlumni()) {
      $hasNotification = false;
      $hasZeroNotificationTab = true;
      foreach ($all_alumni_events as $alumni_event) {
        if (!$alumni_event['mznBildirim']) {
          $hasZeroNotificationTab = false;
        }
        if (!$alumni_event['mznBildirim'] && !$alumni_event['mznGorüntüleme']) {
          $hasNotification = true;
        }
      }
    }
  ?>
    <header class="d-flex flex-row-reverse align-items-center header--gradient header--fixed-height p-2 font-weight-bold text-white">
      <div class="dropdown custom-bg--transparent">
        <button class="btn dropdown-toggle text-white pl-0" type="button" id="headerDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src=<?= $profile_img_src ?> alt="" class="header__img m-1">

          <?php echo (isAlumni()) ? $_SESSION['mezun']['isim'] : $_SESSION['admin']['isim'] ?>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="headerDropdownMenuButton">
          <a class="dropdown-item" href=<?php echo (isAlumni()) ? "/myprofile" : "/adminprofile" ?>><i class="fas fa-user-circle pr-2" style="font-size:17px"></i>Profilim</a>
          <div class="dropdown-item item--hover-light-bg" id="logout-btn" data-user-type=<?= (isAlumni()) ? "mezun" : "admin" ?>><i class="fas fa-sign-out-alt pr-2" style="font-size:17px"></i>Logout</div>
        </div>
      </div>

      <?php
      if (isAlumni()) {
      ?>
        <div class="dropdown custom-bg--transparent">
          <button class="btn dropdown-toggle text-white pr-0" type="button" id="notificationDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src=<?= $hasNotification ? "/Assets/icons/notification.svg" : "/Assets/icons/bell.svg"; ?> alt="notification icon" class="header__img m-1">
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdownMenuButton" id="dropdown-menu">

            <?php
            if (!$hasZeroNotificationTab) {
              foreach ($all_alumni_events as $alumni_event) {
                if (!$alumni_event['mznBildirim']) {
                  $eventTitle = $alumni_event['aciklama'];
                  $dotClass = $alumni_event['mznGorüntüleme'] === 'true' ? "" : "fa fa-circle p-1 d-flex justify-content-center text-primary";
            ?>
                  <div class='dropdown-item text-wrap p-0 custom-notification-panel-width item--hover-light-bg' data-notification-href='/eventdetails?eventId=<?= $alumni_event['etkinlikId'] ?>' data-event-id=<?= $alumni_event['etkinlikId'] ?>>
                    <div class='py-2 container-fluid border-bottom' id=<?= $alumni_event['etkinlikId'] . '-notification-container' ?>>
                      <div class="row">
                        <div class='col-2 d-flex justify-content-center align-items-center'>
                          <i class="far fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                        <div class='col-8 flex-grow-1 px-0'>Etkinliğimize davetlisiniz!!<br />
                          <strong><?= $eventTitle ?></strong>
                          <div class="text-primary"><?= $alumni_event['timeStr'] ?></div>
                        </div>
                        <div class="col-2 d-flex justify-content-center">
                          <div class='row flex-column'>
                            <i class="fa fa-times fa-2x p-1 panel__icon--hover-dark-bg" aria-hidden="true" data-close-btn-id=<?= $alumni_event['etkinlikId'] ?>></i>
                            <?php
                            if (!$alumni_event['mznGorüntüleme']) {
                              echo '<i class="fa fa-circle p-1 d-flex justify-content-center text-primary" aria-hidden="true"></i>';
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
          <?php
                }
              }
            } else {
              echo '<div class="dropdown-item py-2 container-fluid d-flex align-items-center">You have no notification.</div>';
            }
          }
          ?>
          </div>
        </div>
    </header>
  <?php } else {
    include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
    exit();
  } ?>