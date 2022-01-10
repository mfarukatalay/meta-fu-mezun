<?php
// veritabanı bağlantısı
include_once '../src/Domain/Database.php';
include_once '../src/Domain/Etkinlik/EtkinlikModeli.php';
include_once '../src/Domain/Etkinlik/MezunEtkinlikModeli.php';
include_once '../src/Domain/Database.php';

try {
  $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
  $event_model = new EventModel($db->getConnection());
  $alumni_event_model = new AlumniEventModel($db->getConnection());
  $all_alumni_events = $alumni_event_model->getAll();
  if (preg_match('/^\/event\/?/i', $_SERVER['REQUEST_URI'])) {
    $events = $event_model->searchEvents($_SESSION['mezun']['mezunId'], isset($_GET['search']) ? $_GET['search'] : '', false);
  } elseif (preg_match('/^\/my-event\/?/i', $_SERVER['REQUEST_URI'])) {
    $events = $event_model->searchEvents($_SESSION['mezun']['mezunId'], isset($_GET['search']) ? $_GET['search'] : '', true);
  } else {
    $events = $event_model->getAll();
  }
  $server_error = false;
} catch (Exception $e) {
  error_log("Exception: " . $e->getMessage());
  include_once '../src/sablonlar/Baslik.php';
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
  exit();
}
?>
<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
  'my_css' => '/css/Alumni/EventPage.css',
  'search_bar' => '/css/Alumni/SearchBar.css'
));
?>
<?php include_once '../src/sablonlar/nav.php'; ?>
<?php if (!$server_error) { ?>
  <div class="searchBarBG">
    <div class="containerSB">
      <div class="row no-gutters" style="white-space: nowrap">
        <div class="col-lg-3 col-md-3 col-sm-12 p-0"></div>
        <div class="col-lg-6 col-md-6 col-sm-12 p-0 input-group" style="margin-top: 60px;">
          <input type="search" placeholder="Search..." class="form-control" id="search_item" name="search" <?= isset($_GET['search']) ? "value='{$_GET["search"]}'" : '' ?> />
          <div class="input-group-append">
            <button type="submit" id="search-button" class="btn btn-secondary">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container my-5" id="main-body">
    <h1><b>Etkinlikler</b></h1>
    <hr />
    <div id="event-page-section">
      <h2>
        <?php
        if (preg_match('/^\/event\/?$/i', $_SERVER['REQUEST_URI'])) {
          echo 'Tüm Etkinlikler';
        } elseif (preg_match('/^\/my-event\/?$/i', $_SERVER['REQUEST_URI'])) {
          echo 'Etkinliklerim';
        }
        ?>
      </h2>
      <div class="row justify-content-md-center text-center" id="no_result"></div>
      <br />
      <div class="row">
        <?php


        // sayfa ilk kurulum
        $pageIndex = (isset($_GET['page'])) ? $_GET['page'] : 1;
        function upcomingOrPast($timeStr)
        {
          $temp = new DateTime($timeStr);
          $pastDateTimeSecond = (int)($temp->format("U"));
          $curMilliSeconds = (microtime(true));
          $secondSinceInvitation = (int)round(($curMilliSeconds - $pastDateTimeSecond));
          if ($secondSinceInvitation > 0) {
            return 'Past Event';
          } else {
            return '<span class="text-success">Upcoming Event</span>';
          }
        }
        for ($i = ($pageIndex - 1) * 8; $i < count($events) && $i < ($pageIndex) * 8; $i++) {
          $event = $events[$i];
        ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <a href="/eventdetails?etkinlikId=<?= $event['etkinlikId'] ?>" target="_self" id="<?= $event['etkinlikId'] ?>-card" class="nostyle">
              <div class="card h-100 card--bg-light-gray">
                <div style="aspect-ratio:1/1;" class="d-flex align-items-center custom-dark-gray">
                  <img src=<?= is_null($event['fotoVeri']) ? './Assets/imgs/default_events.jpg' : 'data::' . $event['fotoTip'] . ';base64,' . base64_encode($event['fotoVeri']) ?> class="card-img-top image__fixed-height m-auto w-100" style="aspect-ratio: 1/1;" alt="eventPhoto">
                </div>
                <div class="card-body d-flex flex-column justify-content-between event-card-body">
                  <div class="cards pb-2">
                    <h5 class="card-title mb-0 font-weight-bold"><?= $event['aciklama'] ?></h5>
                  </div>
                  <div class="">
                    <div class="row cards">
                      <span class="card-title col-12"><?= upcomingOrPast($event['tarihSaat']); ?></span>
                    </div>
                    <div class="row cards">
                      <div class="col-2"><i class="far fa-calendar-alt" style="color: rgb(218, 58, 47);"></i>
                      </div>
                      <div class="col-10" data-datetime="date"><?= $event['tarihSaat'] ?></div>
                    </div>
                    <div class="row cards">
                      <div class="col-2"><i class="far fa-clock text-primary"></i></div>
                      <div class="col-10" data-datetime="time"><?= $event['tarihSaat'] ?></div>
                    </div>
                    <div class="row cards">
                      <div class="col-2"><i class="fas fa-map-marked-alt text-danger"></i></div>
                      <div class="col-10"><?= $event['adres'] ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        <?php } ?>
        <?php
        if (($pageIndex - 1) * 8 < count($events) && count($events) > 8) {
          $nextPageExists = $pageIndex * 8 <= count($events);
          $next2PageExists = $pageIndex * 8 + 8 <= count($events);
        ?>
          <nav aria-label="Page navigation" class="col-12 d-flex justify-content-center">
            <ul class="pagination justify-content-center">
              <div id="previousPage">
                <li class="page-item <?= (int)$pageIndex === 1 ? 'disabled' : '' ?>">
                  <button id="previousEventPage" class="page-link">Önceki</button>
                </li>
              </div>
              <div class="pages list-group list-group-horizontal">
                <li class="page-item">
                  <button class="btn btn-link page-link" data-cur-page-index=<?= $pageIndex ?>><?= $pageIndex ?></button>
                </li>
                <?php
                if ($nextPageExists) { ?>
                  <li class="page-item">
                    <button class="btn btn-link page-link nextEventPage"><?= $pageIndex + 1 ?></button>
                  </li>
                  <?php
                  if ($next2PageExists) { ?>
                    <li class="page-item">
                      <button class="btn btn-link page-link" id="next2EventPage"><?= $pageIndex + 2 ?></button>
                    </li>
                  <?php } ?>
                <?php } ?>
              </div>
              <div id="nextPage">
                <li class="page-item <?= $nextPageExists ? '' : 'disabled' ?>">
                  <button class="page-link nextEventPage">Sonraki</button>
                </li>
              </div>
            </ul>
          </nav>
        <?php
        }
        ?>
      </div>
      <br />
    </div>
  </div>
  </div>
<?php } else {
  include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
} ?>
<?php include_once '../src/sablonlar/AltBilgi.php'; ?>
<?php include_once '../src/sablonlar/GenelScript.php'; ?>
<script type="module" src="/js/Alumni/EventPage.js"></script>

</body>

</html>