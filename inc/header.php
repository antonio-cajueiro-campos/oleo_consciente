<?php
    include_once 'php/main.php';
    $main = new main();
    
    $themeset = 0;
    $currentId = -1;
    //verificando conta
    if (isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $nome_usuario = $usuario->consultar('nome', $currentId);
        $tipoSelf = $usuario->consultar('tipo', $currentId);
        $themeset = $usuario->consultar('tema', $currentId);
    }
    
    $theme = $themeset == 0 ? 'dark' : null;
    $theme = $themeset == 1 ? 'anime' : $theme;
    $theme = $themeset == 2 ? 'contest' : $theme;
    $theme = $themeset == 3 ? 'default' : $theme;
    $theme = $themeset == null ? 'dark' : $theme;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Desenvolvido por Antonio, Caio, Felipe e Tiago -->
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="<?= $system->getCreators(); ?>">
    <meta name="copyright" content="<?= $system->getCreators(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#428542">
    <?php echo "<title>$nome_pagina - $system->nome_site</title>"; ?>

    <!-- Editar posteriormente -->
    <meta name="e-mail" content="<?= $system->site_email; ?>">
    <meta name="keywords" content="<?= $system->page_keywords; ?>">
    <meta name="description" content="<?= $system->page_desc; ?>">
    <meta name="Abstract" content="<?= $system->page_abstract; ?>">
    <meta name="google-site-verification" content="<?= $system->getGverify(); ?>">
    <link rel="canonical" href="<?= $system->site_address; ?>">

    <!-- CSS Sheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $utils->hashnator("css/reset.css"); ?>">
    <link rel="stylesheet" href="<?= $utils->hashnator("css/global.css"); ?>">
    
    <!-- CSS Personal Sheets -->
    <link rel="stylesheet" href="<?= $utils->hashnator("css/personal/antonio.css"); ?>">
    <link rel="stylesheet" href="<?= $utils->hashnator("css/personal/caio.css"); ?>">
    <link rel="stylesheet" href="<?= $utils->hashnator("css/personal/felipe.css"); ?>">
    <link rel="stylesheet" href="<?= $utils->hashnator("css/personal/tiago.css"); ?>">

    <link rel="stylesheet" href="<?= $utils->hashnator("themes/$theme/style.css"); ?>">
    <!-- <script data-ad-client="ca-pub-5310136825481668" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $utils->hashnator("js/functions.js"); ?>"></script>
</head>
<body>
<div class="progress loading" id="progress">
    <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<div class="wrapper">
    <nav id="sidebar">
        <?php 
            if (isset($_POST['sair'])) {
                $usuario->sair();
            }
            if (isset($_POST['logar'])) {
                $login = $_POST['login'];
                $senha = $_POST['password'];
            
                if (!empty($login) && !empty($senha)) {
                    $result = $usuario->logar($login, $senha);
                    $resultId = $result['code'];

                    switch ($resultId) {
                        case 0: $tempId = $result['id']; header("location: perfil.php?user=$tempId"); break;
                        case 1: echo "<script type='text/javascript'>msgShow(31,2,0,'center','','',false,false,false,'$system->site_email');</script>"; break;
                        case 2: echo "<script type='text/javascript'>msgShow(3,0);</script>"; break;
                    }
                } else
                    echo "<script type='text/javascript'>msgShow(1,0);</script>";
            }

            if (!isset($_SESSION['sessao'])) {
                //ID: 0 Descartador Empresa
                //ID: 1 Descartador Pessoa
                //ID: 2 Coletor Empresa
        ?>
        <form action="" method="post" onsubmit="return loginEmpty();">
            <div class="form-group">
                <label for="emailLogin">Email/Usuário</label>
                <input type="text" class="form-control" id="emailLogin" aria-describedby="emailHelp" name="login" placeholder="Email/Usuário">
            </div>
            <div class="form-group">
                <label for="senhaLogin">Senha</label>
                <input type="password" class="form-control" id="senhaLogin" name="password" placeholder="Senha">
                <a href="recuperar_conta.php"><small id="emailHelp" class="form-text text-muted">Esqueci minha senha.</small></a>
            </div>
            <div class="row siderow">
                <div class="col hbutd">
                    <button type="submit" name="logar" class="btn btn-primary hbute">Entrar</button>
                </div>
                <div class="col hbutd">
                    <a href="cadastrar.php" class='btn btn-outline-light text-light hbutc' href='cadastrar.php'>Cadastrar</a>
                </div>
            </div>
        </form>
        <?php } else { ?>
        <span class="bv-txt text-light">Bem-vindo, <a class="user-a" href="perfil.php?user=<?= $currentId; ?>"><?php if ($tipoSelf == 3) { echo $utils->formatAdm($nome_usuario); } else { echo $str = strlen($nome_usuario) > 10 ? substr($nome_usuario, 0, 10)."..." : $nome_usuario; } ?></a></span>
        <li class="nav-item dropdown unli">
            <a href="#" class="btn nav-link dropdown-toggle text-light btn-outline-light menuDrop" data-toggle="dropdown">Minha conta</a>
            <div class="dropdown-menu dropdown-menu-center dropDown">
                <a href="perfil.php?user=<?= $currentId; ?>" class="dropdown-item dropDown">Meu Perfil</a>
                <a href="configuracoes.php" class="dropdown-item dropDown">Configurações</a>
                <div class="dropdown-divider"></div>
                <form action="" method="post">
                    <button type="submit" name="sair" class="dropdown-item dropDown">Sair</button>
                </form>
            </div>
        </li>
        <?php if ($tipoSelf == 0 || $tipoSelf == 1 || $tipoSelf == 3) { ?>
        <a href="meus_descartes.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Meus descartes</a>
        <a href="procurar_agendas.php?p=0" class="btn nav-link e btn-outline-light text-light menuNavButtom">Procurar Agendas</a>
        <?php } if ($tipoSelf == 0 || $tipoSelf == 3) { ?>
        <a href="procurar_empresa_de_coleta.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Procurar Empresas</a>
        <?php } if ($tipoSelf == 2 || $tipoSelf == 3) { ?>
        <a href="agendas_de_coleta.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Minhas agendas</a>
        <!-- <a href="#" class="btn nav-link e btn-outline-light text-light menuNavButtom">Gráficos (em breve)</a> -->
        <a href="mapa_de_coleta.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Mapa de Coleta</a>
        <?php } if ($tipoSelf == 2 || $tipoSelf == 3) { ?>
        <a href="premium.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Adquira premium</a>
        <?php } if ($tipoSelf == 0 || $tipoSelf == 1 || $tipoSelf == 3) { ?>
        <a href="doar.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Apoie o projeto</a>
        <?php } if ($tipoSelf == 3) { ?>
        <a href="painel_administrativo.php" class="btn nav-link e btn-outline-light text-light menuNavButtom">Painel administrativo</a>
        <?php }} ?>
        <?php if (!isset($_SESSION['sessao'])) { ?>
        <div class="line apoio"></div>
        <a href="sobre.php" class="btn nav-link e btn-outline-light text-light">Conheça o projeto</a>
        <?php } ?>
    </nav>
    <div id="content">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" onclick="toggleLogo()" class="navbar-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <a href="index.php" id="logo" class="navbar-brand"><img src="images/logotipo.png" alt="Logotipo" title="<?= $sys_name; ?>" class="logo"></a>
                <?php if (isset($_SESSION['sessao'])) { ?>
                <li class="nav-item dropdown notify-list">
                    <button class="btn nav-link text-light btn-outline-light not-bu notify-bu d-inline-block d-lg-none ml-auto bellB" type="button" data-toggle="dropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <h5 class="not-buu text-light" id="notifyCountPortrait"></h5>
                        <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-bell" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                            <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shownot upper-notify" id="notifyBoxPortrait"></div>
                </li>
                <?php } ?>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <?php if (isset($_SESSION['sessao'])) { ?>
                        <li class="nav-item dropdown notify-list">
                            <a href="#" class="btn nav-link text-dark btn-outline-light not-bu notify-bu" data-toggle="dropdown">
                                <h5 class="not-buu text-light" id="notifyCountLandscape"></h5>
                                <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-bell" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                                    <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shownot"  id="notifyBoxLandscape"></div>
                        </li>
                        <?php } else { ?>
                        <li class='nav-item active'>
                            <a class='nav-link text-light' href='cadastrar.php'>Cadastre-se!</a>
                        </li>
                        <?php } ?>
                        <li class="nav-item about">
                            <a class="nav-link text-light" href="sobre.php">Sobre</a>
                        </li>
                    </ul>
                <?php if (isset($_SESSION['sessao'])) { echo "</div>"; } ?>
            </div>
        </nav>
      