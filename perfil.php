<?php include 'inc/header.php'?>
<?php $main->requiredAuth(); ?>
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
            $nivel_usuario = $usuario->consultar('nivel', $userPage);
            $nome = $usuario->consultar('nome', $userPage);
            $estado = $usuario->consultar('estado', $userPage);
            $cidade = $usuario->consultar('cidade', $userPage);
            $estado = $utils->codeToLocale($estado, 'estado');
            $cidade = $utils->codeToLocale($cidade, 'cidade');
            $estado = $utils->convertUf($estado, 'reverse');
            $material_agua = $material_usuario * 25000;

    ?>
    <div class="tab-content fundo-conteudo-perfil espaco2 contentBox">
        <div class="tab-pane container active ">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row-12 text-center">
                                <label for="enviar" class="label-perfil"><img src="images/border.png" width="170" height="170"><span class="nivel text-light"><?php echo $nivel_usuario; ?></span></label>
                                <input id="enviar" type="file" style="display:none;">
                            </div>
                            <div class="row-12 text-center">
                                <p class="p-perfil"><?php $max = 15; if (strlen($nome) > $max) { echo substr($nome, 0, $max)."..."; } else { echo $nome; } ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row-12 text-center">
                                <img src="images/selo.png" width="170" height="170">
                            </div>
                            <div class="row-12 text-center">
                                <p class="p-perfil">Selo Placeholder</p>
                            </div>
                        </div>
                    </div>
                    <div class="row-12">
                        <div class="progress">
                            <div class="progress-bar text-dark" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="20"><?php echo $material_usuario; ?>L de 2.0L</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item li-perfil">Já salvou cerca de <?php echo $material_agua;?> litros de água</li>
                        <li class="list-group-item li-perfil">Está no nível <?php echo $nivel_usuario ?></li>
                        <li class="list-group-item li-perfil">
                        <?php if ($userPage == $currentId) {
                            echo "500ml para o próximo nível";
                        } else {
                            echo "Online há cerca de 2 dias";
                        } ?>
                        </li>
                        <li class="list-group-item li-perfil"><?php echo $estado." - ".$cidade; ?></li>
                        <li class="list-group-item li-perfil"><span style="color:gold;"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star-half-o" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></li></span>
                    </ul>
                </div>
            </div>
            <div class="row espaco2">
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