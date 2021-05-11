<?php include 'inc/header.php'?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
    </ol>
</nav>
<div class="container contentBox">
    <section class="about-page">
        <div class="row">
            <div class="col-8">
                <h3>Conheça sobre o nosso projeto</h3>
                <p>Esse projeto surgiu em 2020, como TCC para um curso técnico em Análise e Desenvolvimentos de Sistemas na Etec Dra. Ruth Cardoso. 
                    Inicialmente o foco era auxiliar pequenos coletores de óleo e produtores de sabão caseiro, mas foi adaptado para atender todo mercado de reciclagem 
                    de óleo de cozinha caseiro auxiliando o processo de coleta e incentivando usuários domesticos a adotarem boas práticas em relação ao descarte 
                    de gordura e óleo de fritura usado.
                </p>
            </div>
            <div class="col-4 text-center">
                <img src="images/brasao-etec.jpg" alt="brasão da etec dra. ruth cardoso" class="brasao">
                <img src="images/logo.png" alt="logo do projeto óleo consciente" class="logoS">
            </div>
        </div>
    </section>
    <section class="dev-photo">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-2 text-center"><img src = "images/developers/tiago.jpg" alt=""><p>Tiago Antonio</p></div>
            <div class="col-2 text-center"><img src = "images/no-avatar.png" alt=""><p>Antonio Carlos</p></div>
            <div class="col-2 text-center"><img src = "images/no-avatar.png" alt=""><p>Caio Henrique</p></div>
            <div class="col-2 text-center"><img src = "images/no-avatar.png" alt=""><p>Felipe Rigorini</p></div>
            <div class="col-2"></div>
        </div>
    </section>
</div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>