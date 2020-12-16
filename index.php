<?php include 'inc/header.php'?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">

<link rel="stylesheet" href="css/swiper-bundle.min.css">

  <nav aria-label="breadcrumb" class="breadcrumb-main">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php"><?php echo $nome_pagina;?></a></li>
      </ol>
  </nav>

  <div class="contentBox">
    <div class="banner"></div>
    <div class="row-12 text-center">
      <div class="top-10">
        <h5 class=contentTitle>Projeto <?php echo $system->nome_site; ?></h5>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-6 colContentLeft">
          <!-- <h3 class="text-center"></h3> -->
          <p class="contentP">Atualmente, cerca de 90% de todo óleo de cozinha utilizado no Brasil, é descartado de forma incorreta.</p>
          <p class="contentP">O projeto <a href=sobre.php style='text-decoration:underline'><?php echo $system->nome_site; ?></a> tem como propósito incentivar as pessoas a descartar de forma correta o material, fazendo assim bem a natureza e ajudando empresas que precisam da matéria prima para produzir seus produtos.</p>
          <?php if(!isset($_SESSION['sessao'])) { ?>
          <p class="contentP"><a href='cadastrar.php' style='text-decoration:underline'>Cadastre-se</a> e venha fazer parte!</p>
          <?php } ?>
        </div>
        <div class="col-lg-6 colContentRight">
              <div class="contentV">
                <iframe class="yt-video" src="https://www.youtube.com/embed/n7MfGs0xE1E" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
        </div>
    </div>
    <div class="row-12 text-center">
      <div class="top-10">
        <h5 class=contentTitle>Veja os usuários de maior nível!</h5>
      </div>
    </div>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
        <img class="swiper-slide" src="images/personalCardSample.png" alt="" srcset="">
      </div>
    </div>
  <!-- <div class="swiper-pagination"></div> -->

  <!-- Swiper JS -->
  <script src="js/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper('.swiper-container', {
      slidesPerView: 7,
      spaceBetween: 15,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  </script>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
    </div>
  </div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>

