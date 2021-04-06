<?php include 'inc/header.php'?>
<?php //$main->requiredAuth(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
    <nav aria-label="breadcrumb" class="breadcrumb-main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
        </ol>
    </nav>
    <?php
    if (isset($_GET['user'])) {
        $userPage = $_GET['user'];

        if ($usuario->consultar('exists', $userPage)) {
            $material_usuario = $usuario->consultar('material', $userPage);
            $lvl = $usuario->consultar('nivel', $userPage);
            $xp = $usuario->consultar('xp', $userPage);
            $nome = $usuario->consultar('nome', $userPage);
            $estado = $usuario->consultar('estado', $userPage);
            $cidade = $usuario->consultar('cidade', $userPage);
            $estado = $utils->codeToLocale($estado, 'estado');
            $cidade = $utils->codeToLocale($cidade, 'cidade');
            $estado = $utils->convertUf($estado, 'reverse');
            $createDate = $usuario->consultar('criacao', $userPage);
            $img = $usuario->getImage($userPage);
            $reviewList = $usuario->getReviews($userPage);
            $tipoProfile = $usuario->consultar('tipo', $userPage);
            $myRev = 0;
            $index = 0;
            
            foreach ($reviewList as $rev) {
                $myRev += $rev['stars'];
                $index++;
            }
            
            $reviewList = array_slice($reviewList, 0, 5);
            if ($myRev != 0)
            $myRev = $myRev / $index;
            $myRev = $utils->getStarsImage($myRev);
            
            $eloArr = $usuario->getElo($userPage);

            $elo = $eloArr['elo'];
            $selo = $eloArr['selo'];

            $material_agua = $utils->organizeMaterial($material_usuario);

            $createDate = $utils->inverteData($createDate);

            $verifyDefaultPhoto = $img == "images/no-avatar.png?v=119d48165ba38e99b35691f59ebc7674" ? "disabled" : "";


            $need = (1/3) * (pow($lvl, 3) - 6 * pow($lvl, 2) + 17 * $lvl - 12);
            $need = $lvl == 1 ? 1 : $need;

            $porc = ($need - $xp) / $need * 100;
            $porc = abs($porc - 100);

    ?>
    <div class="tab-content fundo-conteudo-perfil espaco2 contentBox">
        <div class="tab-pane container active ">
            <div class="row">
                <div class="col-lg-6 perfilblock">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row-12 text-center picrow" <?php if ($userPage == $currentId) echo "data-toggle=dropdown"; ?>>
                                <label for="profile-picture" class="label-perfil <?php if ($userPage == $currentId) echo "user-pfp"; ?>">
                                    <img class="profile-picture" id="pfp" src="<?php echo $img; ?>">
                                    <img class="border-off" src="images/bordas/<?php echo $elo;?>-border.png" alt="">
                                    <span class="nivel text-light"><?php echo $lvl; ?></span>
                                </label>
                            </div>
                            <input id="profile-picture" onchange="verifyUserImage(this)" <?php if ($userPage == $currentId) echo "type=file"; else echo "type=hidden";?> style="display:none;">
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item" id="changePic">Alterar foto</button>
                                <button class="dropdown-item" <?php echo $verifyDefaultPhoto; ?> id="removePic">Remover foto</button>
                            </div>
                            <div class="row-12 text-center p-text-b">
                                <p class="p-perfil"><?php $max = 15; if (strlen($nome) > $max) { echo substr($nome, 0, $max)."..."; } else { echo $nome; } ?></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row-12 text-center selorow">
                                <img src="images/selos/<?php echo $elo; ?>-selo.png" width="170" height="170">
                            </div>
                            <div class="row-12 text-center p-text-b">
                                <p class="p-perfil"><?php echo $selo; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row-12">
                        <div class="progress xp">
                            <div class="porcXp"><?php echo $xp; ?>L de <?php echo $need; ?>L</div>
                            <div class="progress-bar text-dark" role="progressbar" style="width: <?php echo $porc; ?>%;" aria-valuenow="<?php echo $porc; ?>" aria-valuemin="0" aria-valuemax="<?php echo $porc; ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 infoblock">
                    <ul class="list-group">
                        <li class="list-group-item li-perfil">Já salvou cerca de <?php echo $material_agua;?> litros de água</li>
                        <li class="list-group-item li-perfil">Está no nível <?php echo $lvl ?></li>
                        <li class="list-group-item li-perfil"><?php echo $estado." - ".$cidade; ?></li>
                        <li class="list-group-item li-perfil">Membro desde <?php echo $createDate; ?></li>
                        <li class="list-group-item li-perfil">Rate: <span style="color:gold;"><?php echo $myRev; ?></span></li>
                    </ul>
                </div>
            </div>
            <div class="row addReview">
                <div class="userProfile">
                </div>
            </div>
            
            <div style="<?php if ($tipoProfile != 2) echo "display:none;"; ?>">
            
            <p style=color:black>Last 5 reviews</p>
            <div class="row-12 reviewList">
                <?php
                //INSERT INTO `tb_reviews`(`cd_usuario`, `cd_review`, `ds_review`, `dt_review`) VALUES (14,13,'um koko blablabla la2','2020-12-12')
                    if (count($reviewList) == 0) {
                        echo "Nenhum review encontrado.";
                    }
                    foreach ($reviewList as $review) {
                        $reviewId = $review['id'];
                        $reviewFrom = $review['from'];
                        $reviewPicture = $usuario->getImage($reviewFrom);
                        $reviewUsername = $usuario->consultar('nome', $reviewFrom);
                        $reviewLevel = $usuario->consultar('nivel', $reviewFrom);
                        $reviewElo = $usuario->getElo($reviewFrom);
                        $reviewElo = $reviewElo['elo'];
                        $reviewMsg = $review['msg'];
                        $reviewStars = $review['stars'];
                        $reviewDate = strtotime($review['date']);
                        $reviewDate = strftime('%d de %B', $reviewDate);
                        $reviewStars = $utils->getStarsImage($reviewStars);
                        $reviewUsernameMax = 18;

                        $reviewMsgWithoutMsg = $reviewMsg == '' ? 'revMsgWithoutMsg' : '';
                        $reviewDataWithoutMsg = $reviewMsg == '' ? 'revDataWithoutMsg' : '';

                        $templateDelete = $reviewFrom == $currentId ? "<div onclick='systemPopup(13, $reviewId)' class='reviewX'></div>" : "" ;

                        $reviewUsername = strlen($reviewUsername) > $reviewUsernameMax ? substr($reviewUsername, 0, $reviewUsernameMax)."..." : $reviewUsername ;

                        $template = "
                        <div class='col review'>
                            $templateDelete
                            <div class='reviewPictureBox'>
                                <a target=_blank href=perfil.php?user=$reviewFrom>
                                <img class='reviewPicture' src='$reviewPicture' alt='profile picture'>
                                <img class='reviewBorder' src='images/bordas/$reviewElo-border.png' alt='profile border'>
                                <span class='reviewLevel'>$reviewLevel</span>
                                </a>
                            </div>
                            <div class='reviewUsername'><a target=_blank href=perfil.php?user=$reviewFrom>$reviewUsername</a></div>
                            <div class='reviewStars'>$reviewStars</div>
                            <div class='reviewMsg $reviewMsgWithoutMsg'>$reviewMsg</div>
                            <div class='reviewData $reviewDataWithoutMsg'>$reviewDate</div>
                        </div>
                        ";
                        
                        echo $template;
                    }
                ?>
            </div>
            </div>
            <div class="row-12 espaco2">
                <div class="col adsbygoogle2"></div>
            </div>
        </div>
    </div>
    <?php
        } else {
            echo "usuário não encontrado";
        }
    } else {
        echo "parâmetros incorretos";
    }
    ?>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>