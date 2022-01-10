<nav class="navbar navbar-light navbar-expand-md bg-light container-fluid">
  <div class="container-fluid">
    <a class="navbar-brand" href="http://www.firat.edu.tr/tr" target="_blank">
      <img src="/Assets/imgs/fu_logo.png" alt="" class='nav__img'>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item 
        <?=
        strpos($_SERVER['REQUEST_URI'], 'home') ? " active" : "";
        ?> 
        ">
          <a class="nav-link" aria-current="page" href="/home">Anasayfa</a>
        </li>
        <li class="nav-item 
        <?=
        strpos($_SERVER['REQUEST_URI'], 'mezun') ? " active" : "";
        ?> ">
          <a class="nav-link" href="/alumni">Mezunlar</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle
          <?=
          strpos($_SERVER['REQUEST_URI'], 'isIlani') ? " active" : "";
          ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            İş İlanları
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/job">Tüm İş İlanları</a>
            <a class="dropdown-item" href="/myjob">İş İlanlarım</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle
          <?=
          strpos($_SERVER['REQUEST_URI'], 'etkinlik') ? " active" : "";
          ?>" href="#" id="eventDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Etkinlik
          </a>
          <div class="dropdown-menu" aria-labelledby="eventDropdown">
            <a class="dropdown-item" href="/event">Tüm Etkinlikler</a>
            <a class="dropdown-item" href="/my-event">Etkinliklerim</a>
          </div>
        </li>
        <li class="nav-item">
          <span class="nav-link" id="contact-us-nav-item">Bize Ulaşın</span>
        </li>
      </ul>
    </div>
  </div>
</nav>