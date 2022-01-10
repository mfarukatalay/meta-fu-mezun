<?php
include_once '../src/Domain/Database.php';
include_once '../src/Domain/isIlani/isIlaniModeli.php';
include_once '../src/Domain/Etkinlik /EtkinlikModeli.php';
include_once '../src/Domain/Mezun /MezunModeli.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);

try {
    $event_model = new EventModel($db->getConnection());
    $all_activities_event = $event_model->get6LatestEvent();
    for ($i = 0; $i < count($all_activities_event); $i++) {
        $allImage_event[$i] = $event_model->EventImages($all_activities_event[$i]['etkinlikId']);
    }
    for ($i = 0; $i < count($all_activities_event); $i++) {
        $all_activities_event[$i]['fotoId'] = $allImage_event[$i];
    }

    $alumni_model = new AlumniModel($db->getConnection());
    $all_activities_alumni = $alumni_model->AlumniData();
    for ($i = 0; $i < count($all_activities_alumni); $i++) {
        $allImage_alumni[$i] = $alumni_model->AlumniImages($all_activities_alumni[$i]['fotoId']);
    }
    for ($i = 0; $i < count($all_activities_alumni); $i++) {
        $all_activities_alumni[$i]['fotoId'] = $allImage_alumni[$i];
    }

    $job_model = new JobModel($db->getConnection());
    $all_activities_job = $job_model->Nicole();
    $allImage_job = $job_model->NicoleImages();
    for ($i = 0; $i < count($all_activities_job); $i++) {
        $all_activities_job[$i]['fotoId'] = $allImage_job[$i];
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    include_once '../src/sablonlar/Baslik.php';
    include_once '../src/Domain/Genel_Sayfa/sunucu_hatasi.php';
    exit();
}


<script type="text/javascript">
    var job_array = <?php echo json_encode($all_activities_job) ?>;
</script>
<script type="text/javascript">
    var event_array = <?php echo json_encode($all_activities_event) ?>;
</script>
<script type="text/javascript">
    var alumni_array = <?php echo json_encode($all_activities_alumni) ?>;
</script>

<script type="module" src="/js/Alumni/homePage.js"></script>

<?php
include_once '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/HomePage.css'
));
?>
<?php
include_once '../src/sablonlar/nav.php';
?>

<div class="container-fluid d-flex flex-column align-items-center p-0" id="main-body">

    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/Assets/imgs/hp_slide7.jpg" class="image--max-size-100-percent d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="/Assets/imgs/hp_slide8.jpg" class="image--max-size-100-percent d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="/Assets/imgs/hp_slide5.jpg" class="image--max-size-100-percent d-block w-100" alt="...">
            </div>
        </div>


        <div class="bottomleft">
            <h1>
                <span class="typewrite" data-period="2000" data-type='[ "Hi, Welcome back!",  
            "Welcome to FSKTM Online Alumni System!", "UM is One!" ]'>
                    <span class="wrap"></span></span>
                </a>
            </h1>
        </div>
    </div>


    <!-- Only insert photos with same width and height -->
    <span class="Title">Etkinlikler</span>

    <div class="w-50 bg-grey d-flex flex-column align-items-center">
        <div class="swiper-container p-5">
            <div class="swiper-wrapper" id="etkinlik">
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

        </div>
        <button id="viewMoreEvents" class="viewMorebtn m-4 rounded-pill">Daha Fazla 
            Göster</button>
    </div>



    <span class="Title">İş İlanları</span>
    <div class="w-50">
        <div class="row mb-4 mt-1" id="isIlani_row_1">
        </div>
        <div class="row mt-1" id="isIlani_row_2">
        </div>
    </div>
    <button id="viewMoreJob" class="viewMorebtn m-4 rounded-pill">Daha Fazla
        Göster</button>



    <span class="Title">Mezunlar</span>
    <div class="w-50 bg-grey d-flex flex-column align-items-center">
        <div class="swiper-container p-5">
            <div class="swiper-wrapper" id="mezun">
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <button id="viewMoreAlumni" class="viewMorebtn m-4 rounded-pill">Daha Fazla
			Göster</button>
    </div>
    <div class="mb-5">
    </div>
</div>

<?php include_once '../src/sablonlar/AltBilgi.php' ?>
<?php include_once '../src/sablonlar/GenelScript.php' ?>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script> -->

<script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</body>

</html>