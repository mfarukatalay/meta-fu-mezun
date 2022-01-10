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

<div class="row m-0 p-0 justify-content-center">
  <div class="col-md-4 p-5 text-center">
    <img class="card-img-150 mb-3" src="/Assets/imgs/404.svg" alt="404 Not Found">
    <?php
      echo '<a href='.(isset($_SESSION['mezun'])?"/home":"/admin").' class="btn btn-primary btn-animation px-3" type="button"><i class="fas fa-angle-left"></i> Back to home</a>'
    ?>
  </div>
</div>