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
    <div class="banner">
    <img src="images/content/banner.webp" alt="banner" class="banner-img">
    </div>
    <div class="row-12 text-center">
      <div class="top-10">
        <h5 class=contentTitle>Projeto <?php echo $system->nome_site; ?></h5>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-6 colContentLeft">
          <!-- <h3 class="text-center"></h3> -->
          <p class="contentP">Atualmente, cerca de 90% de todo óleo de cozinha utilizado no Brasil, é descartado de forma incorreta.</p>
          <p class="contentP">O projeto <a href="sobre.php" class="link-content"><?php echo $system->nome_site; ?></a> tem como propósito incentivar as pessoas a descartar de forma correta o material, fazendo assim bem a natureza e ajudando empresas que precisam da matéria prima para produzir seus produtos.</p>
          <?php if(!isset($_SESSION['sessao'])) { ?>
          <p class="contentP"><a href="cadastrar.php" class="link-content">Cadastre-se</a> e venha fazer parte!</p>
          <?php } ?>
        </div>
        <div class="col-lg-6 colContentRight">
              <div class="contentV">
                <div class="yt-video">
                  <iframe width="560" height="315"  src="https://www.youtube.com/embed/iNOh7VTdMeI" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
      <h3>Perguntas Frequentes</h3><br>
      <h4>1- Sou coletor de resíduos individual, e quero utilizar a plataforma como devo proceder?</h4>
      <p>Se você é coletor de óleo individual e deseja utilizar essa plataforma para realizar coletas é necessário ter um CNPJ regularizado e se 
      <a href="cadastrar.php">cadastrar</a> com a opção para coletores, conseguir um CNPJ hoje em dia é fácil, 
      principalmente se você se caracteriza como <a rel="noopener noreferrer" target="_blank" class="link-content" href="https://www.guiaempreendedor.com/guia/clico-responde-mei-cnpj">microempreendedor individual</a>, 
      para se cadastrar como MEI acesse esse <a rel="noopener noreferrer" target="_blank" class="link-content" href="https://www.gov.br/empresas-e-negocios/pt-br/empreendedor">link</a></p><br>
      <h4>2- Quando eu tento me cadastrar, eu recebo um aviso de que meu CNPJ está desregularizado. Porquê?</h4>
      <p>Para melhor segurança na plataforma, é necessário ter um CNPJ regularizado para poder utilizá-lo, 
      verifique os motivos que estão causando esse problema <a rel="noopener noreferrer" target="_blank" class="link-content" href="http://servicos.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp">
      consultando</a> a situação cadastral do seu CNPJ</p><br>
      </div>
      <div class="col-lg-4 img-content-qst">
        <img class="img-qst" src="images/content/pic-qst-woman.webp" alt="" srcset="">
      </div>
    </div>
    <!--Parceiros-->
    <div class="festa-verde row">
      <div class="colText col-xl-9">
        <h3>Nossos parceiros</h3><br>
        <h4>Festa verde:</h4>
        <p><a rel="noopener noreferrer" target="_blank" class="link-content" href="https://festaverde.ga/?i=1">O Festa Verde</a> é um sistema que junta divulgação de artigos para festa junto com incentivo a reciclagem, 
        através de benefícios para empresas que reciclam seus materiais descartáveis.</p>
      </div>
      <div class="col-xl-3 text-center">
        <img src="images/content/festa_verde.webp" alt="" class="img-parceiros">      
      </div>
    </div><br>
    <div class="ecological row">
      <div class="colText col-xl-9">
        <h4>Ecological:</h4>
          <p>O Ecological é uma comunidade virtual para consciência ambiental, que permite publicações por parte de pessoas comuns, 
            companhias, ONGs, e moderadores, sobre dicas de todos os tipos possíveis para reutilização, informe sobre impactos do uso de 
            determinados produtos, produtos naturais, espaço para divulgação de serviço autônomo que envolvem o tema.
          </p>
      </div>
      <div class="col-xl-3 text-center">
        <img src="images/content/ecological.webp" alt="" class="img-parceiros">
      </div>
    </div>
    <!--Dashboard-->
    <div class="row-12 text-center">
      <div class="top-10">
        <h5 class="contentTitle">Veja os 10 usuários de maior nível!</h5>
      </div>
    </div>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php
        //"SELECT * FROM tb_descartes AS d JOIN tb_enderecos AS e ON d.cd_usuario = e.cd_usuario WHERE e.cd_estado = '$estado' AND e.cd_cidade = '$cidade' ORDER BY cd_descarte DESC LIMIT $pagina, $qt_por_pagina";
          $sql = "SELECT qt_nivel, cd_usuario FROM tb_configs ORDER BY qt_nivel DESC LIMIT 10";
          $query = $mysqli->query($sql);
          $qtUsers = $query->num_rows;
          $index = 0;
          if ($qtUsers > 0) {
            foreach ($query as $user) {
              $sliderLevel = $user['qt_nivel'];
              $sliderId = $user['cd_usuario'];
              $sliderElo = $usuario->getElo($sliderId);
              $sliderElo = $sliderElo['elo'];
              $sliderPicture = $usuario->getImage($sliderId);
              $sliderUsername = $usuario->consultar('nome', $sliderId);
              $sliderUsernameArr = explode(' ', $sliderUsername);
              $sliderUsername = $sliderUsernameArr[0];
              $material = $usuario->consultar('material', $sliderId);
              $material_agua = $utils->organizeMaterial($material);
              $crown = "";
              if ($index == 0) {
                $crown = "<img class=swiperCrown src='images/crown.png'>";
              }
              echo "
              <div class='swiper-slide' onclick='slideThenRedirect($sliderId, $index)'>
                <div class='swiperProfile'>
                  <img class='sliderPicture' src='$sliderPicture' alt='profile picture'>
                  <img class='sliderBorder' src='images/bordas/$sliderElo-border.png' alt='profile border'>
                  <span class='sliderLevel'>$sliderLevel</span>
                  $crown
                </div>
                <div class='swiperInfo'>
                  <div class='swiperName'>$sliderUsername</div>
                  <div class='swiperMaterial'>Já salvou cerca de $material_agua litros de água!</div>
                </div>
              </div>";
              $index++;              
            }
          }
        ?>
      </div>
      <!-- div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div> -->
    </div>
  <!-- <div class="swiper-pagination"></div> -->

  <!-- Swiper JS -->
  <script src="js/swiper-bundle.min.js"></script>
  <!-- Initialize Swiper -->
  <script>
    function slideThenRedirect(id, index) {
      swiper.slideTo(index, 200);
      window.location.href = `perfil.php?user=${id}`;
    }
    var swiperOption = {
      slidesPerView: 1,
      grabCursor: true,
      centeredSlides: true,
      freeMode: true,
      spaceBetween: 20,
      //mousewheel: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        0: { slidesPerView: 1 },
        485: { slidesPerView: 2 },
        950: { slidesPerView: 3 },
        1200: { slidesPerView: 4 },
        1400: { slidesPerView: 5 }
      },
      effect: 'coverflow',
      coverflowEffect: {
        rotate: 0,
        stretch: -20,
        depth: 200,
        modifier: 1,
        //slideShadows: true
      },
    };
    var swiper = new Swiper('.swiper-container', swiperOption);
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

