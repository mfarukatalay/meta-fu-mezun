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
<?php
include '../src/araclar/Degisken.php' ?>
<?php
includeWithVariables('../src/sablonlar/Baslik.php', array(
    'my_css' => '/css/Alumni/MyJobPage.css',
    'searchBar' => '/css/Alumni/SearchBar.css'
));
?>
<?php
include '../src/sablonlar/nav.php';
?>


<div class="row m-0 p-0 justify-content-center">
  <div class="col-md-4 p-5 text-center">
    <img class="card-img-150 mb-3" src="/Assets/imgs/saechNotFound.png" alt="Search Not Found">
  </div>
  <?php include_once '../src/sablonlar/AltBilgi.php' ?>
  <?php include_once '../src/sablonlar/GenelScript.php' ?>
</div>