<?php
$homePage = preg_match('/^\/admin$/i', $_SERVER['REQUEST_URI']);
$alumniPage = preg_match('/^\/admin\/alumnilist/i', $_SERVER['REQUEST_URI']);
$eventPage = preg_match('/\/event/i', $_SERVER['REQUEST_URI']);
?>
<title>Sayfa Bulunamadı - Mezun Sistemi</title>
<style>
    @keyframes example {
        from {
            transform: translateX(0px);
        }

        to {
            transform: translateX(-10px);
        }
    }

    .btn-animation {
        animation-name: example;
        animation-duration: 1s;
        animation-direction: alternate;
        animation-iteration-count: infinite;
    }
</style>
</head>

<body>
    <main class="container-fluid height-after-minus-header" id='main-body'>
        <div class="row h-100">
            <div class="custom-dark-gray px-0" id="left-nav">
                <ul class="d-flex flex-column list-unstyled">
                    <li class="pl-3 py-2 container-fluid my-2 <?= $homePage ? "text-warning" : "text-white"; ?>">
                        <a class="nostyle row no-gutters d-flex flex-column justify-content-center align-items-center text-center" href="/admin">
                            <i class="d-flex align-items-center justify-content-center fas fa-home fa-2x"></i>
                            <span class="d-flex">Anasayfa</span>
                        </a>
                    </li>
                    <li class="pl-3 py-2 container-fluid my-2 <?= $alumniPage ? "text-warning" : "text-white"; ?>">
                        <a class="nostyle row no-gutters d-flex flex-column justify-content-center align-items-center text-center" href="/admin/alumnilist">
                            <i class="d-flex align-items-center justify-content-center fas fa-users fa-2x col-6"></i>
                            <span class="d-flex">Mezunlar</span>
                        </a>
                    </li>
                    <li class="pl-3 py-2 container-fluid my-2 <?= $eventPage ? "text-warning" : "text-white"; ?>">
                        <a class="nostyle row no-gutters d-flex flex-column justify-content-center align-items-center text-center" href="/admin/event">
                            <i class="d-flex align-items-center justify-content-center far fa-calendar-alt fa-2x col-6"></i>
                            <span class="d-flex">Etkinlikler</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="container-fluid" id="right-content">
                <div class="row m-0 p-0 justify-content-center">
                    <div class="col-lg-5 col-md-8 p-5 text-center">
                        <img class="card-img-150 mb-3" src="/Assets/imgs/404.svg" alt="404 Not Found">
                        <a href="/admin" class="btn btn-primary btn-animation px-3" type="button"><i class="fas fa-angle-left"></i> Geri Dön</a>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once'../src/sablonlar/GenelScript.php'; ?>
    </main>