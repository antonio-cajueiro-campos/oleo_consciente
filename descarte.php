<?php include 'inc/header.php'?>
<?php $main->requiredAuth(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page">
        <?php 
            if (isset($_GET['id'])) {
                $descarte = $_GET['id'];
                echo  $nome_pagina." #".$descarte;
            } else {
                echo "ERROR";
            }
        ?>
        </li>
    </ol>
</nav>
<div class="row-12">
<div class="col descartePage contentBox">
<?php
    //ID: 0 Descartador Empresa
    //ID: 1 Descartador Pessoa
    //ID: 2 Coletor Empresa
    include_once 'php/classes/class.notify.php';
    $notify = new notify();

    if ((!isset($_GET['agenda']) && $tipoSelf == 2) || (empty($_GET['agenda']) && $tipoSelf == 2 )) {
        echo "agenda não selecionada";
    } else {
    $status = 0;
    if (isset($_GET['id'])) {
        if (isset($_GET['agenda'])) {
            $agendaId = $_GET['agenda'];
        }
        if (isset($_POST['salvar'])) {
            $obs = $_POST['obs'];
            $sql = "SELECT cd_descarte, cd_usuario FROM tb_descartes WHERE cd_descarte = '$descarte' AND cd_usuario = '$currentId'";
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count > 0) {
                $infoArr = $utils->verifyBadWords($obs);
                $obs = $infoArr['words'];
                $obsVerify = $infoArr['bad'];
                if ($obsVerify) {
                    echo "<script type='text/javascript'>msgShow(29,0,10000)</script>";
                    echo "<script type='text/javascript'>playNotify()</script>";

                    $sqlAdv = "SELECT qt_advertence FROM tb_usuarios WHERE cd_usuario = '$currentId'";
                    $queryAdv = $mysqli->query($sqlAdv);
                    $rowAdv = $queryAdv->fetch_array(MYSQLI_ASSOC);
                    $adv = $rowAdv['qt_advertence'];
                    $adv++;
                    $sqlAdv = "UPDATE tb_usuarios SET qt_advertence = '$adv' WHERE cd_usuario = '$currentId'";
                    $mysqli->query($sqlAdv);
                }
                $obs = $mysqli->real_escape_string($obs);
                $sql = "UPDATE tb_descartes SET ds_descarte = '$obs' WHERE cd_descarte = '$descarte' AND cd_usuario = '$currentId'";
                $mysqli->query($sql);
                if (!$obsVerify)
                    echo "<script type='text/javascript'>msgShow(36,1,2000);</script>";
            }
        }
        $sql = "SELECT cd_descarte, cd_usuario, ds_descarte, qt_descarte, cd_status, dt_criacao FROM tb_descartes WHERE cd_descarte = '$descarte'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $dispoArr = (array)$row;
        if (array_key_exists('cd_status', $dispoArr)) {
            if ($dispoArr['cd_status'] == null || $dispoArr['cd_status'] == 0) {
                $status = 0;
            } else {
                $status = 1;
            }
        } else {
            $status = 0;
        }
        if ($count > 0) {
            $descarteId = $row['cd_descarte'];
            $userId = $row['cd_usuario'];
            $descarteDs = $row['ds_descarte'];
            $descarteQt = $row['qt_descarte'];
            
            $descarteData = $row['dt_criacao'];

            $descarteData = $utils->inverteData($descarteData);
            
            if (isset($_POST['solicitar'])) {
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "SELECT qt_max FROM tb_agendas WHERE cd_agenda = '$agendaId'";
                    $query = $mysqli->query($sql);
                    $row = $query->fetch_array(MYSQLI_NUM);
                    $max = $row[0];

                    $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_agenda = '$agendaId'";
                    $query = $mysqli->query($sql);
                    $count = $query->num_rows;
                    if ($count < $max) {
                        $notify->criar($agendaId, $userId, $currentId, $id, '0');
                        echo "<script>let data = \"msgShow(53,1)\";localStorage.setItem('msgHtml', data);</script>";
                    } else {
                        echo "<script>let data = \"msgShow(63, 0)\";localStorage.setItem('msgHtml', data);</script>";                        
                    }
                }
            }

            $sql = "SELECT cd_tipo, nm_usuario, ds_telefone FROM tb_usuarios WHERE cd_usuario = '$userId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);

            $nome = $row['nm_usuario'];
            $tipo = $row['cd_tipo'];
            $telefone = $row['ds_telefone'];
            
            if ($tipo == 3) {
                $nome = $utils->formatAdm($nome);;
            }
        
            $sql = "SELECT cd_estado, cd_cidade, ds_bairro, ds_rua, ds_numero, ds_complemento, ds_cep FROM tb_enderecos WHERE cd_usuario = '$userId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $estado = $row['cd_estado'];
            $cidade = $row['cd_cidade'];
            $bairro = $row['ds_bairro'];
            $rua = $row['ds_rua'];
            $numero = $row['ds_numero'];
            $complemento = $row['ds_complemento'];
            $cep = $row['ds_cep'];

            $estado = $utils->codeToLocale($estado, 'estado');
            $cidade = $utils->codeToLocale($cidade, 'cidade');
        
            $cidade = $utils->cidadeView($cidade);
        
            if ($descarteDs == "") {
                $descarteDs = "Sem observação...";
            }
        } else {
            echo "<h4>Erro ao carregar página, link quebrado</h4>";
        }
    } else {
        echo "<h4>Erro ao carregar página, link quebrado</h4>";
    }

    $coletorId = 0;

    $sql = "SELECT cd_agenda FROM tb_descartes WHERE cd_descarte = '$descarteId'";
    $query = $mysqli->query($sql);
    $count = $query->num_rows;
    if ($count > 0) {
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $agendaId = $row['cd_agenda'];
        if ($agendaId != 0) {
            $sql = "SELECT cd_usuario, dt_coleta, hr_inicial, hr_final FROM tb_agendas WHERE cd_agenda = '$agendaId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $coletorId = $row['cd_usuario'];
            $dataColeta = $row['dt_coleta'];
            $horaInicial = $row['hr_inicial'];
            $horaFinal = $row['hr_final'];
            $horaInicial = substr($horaInicial, 0, 5);
            $horaFinal = substr($horaFinal, 0, 5);
            $dataColeta = $utils->inverteData($dataColeta);
            $sql = "SELECT nm_usuario FROM tb_usuarios WHERE cd_usuario = '$coletorId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $nomeColetor = $row['nm_usuario'];
        }
    }
    

if ($status == 0 && $currentId != $coletorId && $currentId != $userId) {
    echo "Descarte indisponível no momento";
} else { ?>
<form method=post>
    <div class="row-12">
        <div class="col text-center text-dark descarteTitulo">
            <h3 class="descartePageTitle">
                <strong>
                    Cartão de descarte#<?php echo $descarte; ?>
                </strong>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 postOrgLeft">
        <div class="obsBlockPage">
            <li class="list-group-item list-group-item-secondary text-center"><strong>Descarte de <?php echo $descarteQt; ?> Litros</strong></li>
            <div class="row-12 obsBlock">
                <textarea name="obs" maxlength="432" <?php $resp = $currentId == $userId && $status == 1 ? "placeholder='Sem observações... (Máximo de 432 caracteres)'" : "readonly"; echo $resp; ?> class="txtObs" rows="8"><?php $resp = $currentId == $userId && $descarteDs == 'Sem observação...'? "" : $descarteDs; echo $resp;?></textarea>
            </div>
        </div></div>
        <div class="col-md-6 postOrgRight">
            <div class="obsBlockPage">
                <ul class="list-group list-group-flush text-dark">
                    <li class="list-group-item"><strong>Criador:</strong> <a style=text-decoration:underline href='perfil.php?user=<?php echo $userId; ?>'><?php echo $nome."#".$userId; ?></a></li>
                    <li class="list-group-item list-group-item-secondary"><strong>Estado: </strong><?php echo $estado; ?></li>
                    <li class="list-group-item"><strong>Cidade: </strong><?php echo $cidade; ?></li>
                    <li class="list-group-item list-group-item-secondary"><strong>Bairro: </strong><?php echo $bairro; ?></li>
                    <li class="list-group-item"><strong>Criado em:</strong> <?php echo $descarteData; ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='col text-center'>
        <div class="row-12 infoMore">
        <?php
            if ($currentId == $userId && $status == 1) {
                echo "<input class='btn btn-primary mgtp' style=margin-bottom:10px type=submit value='Salvar Alterações' name=salvar>";
            } else if (($tipoSelf == 2 || $tipoSelf == 3) && $status == 1) {
                $sql = "SELECT cd_descarte FROM tb_notify WHERE cd_descarte = '$descarteId'";
                $query = $mysqli->query($sql);
                $count = $query->num_rows;
                if ($count > 0 && $status == 1) {
                    echo "<ul class=\"list-group list-group-flush text-dark\">";
                    echo "<li style=color:red class=\"list-group-item text-center\"><strong>Solicitação enviada!</strong></li>";
                    echo "</ul>";
                } else {
                    echo "<input class='btn btn-primary mgtp' style=margin-bottom:10px type=submit value=Solicitar name=solicitar>";
                }
            } else if ($status == 0 && $coletorId == $currentId) {
                echo "<ul class=\"list-group list-group-flush text-dark\">";
                echo "<li class=\"list-group-item text-center\" style=color:#2cbe2c><strong>Cartão de descarte em espera para coleta!</strong></li>";
                echo "<li class=\"list-group-item list-group-item-secondary text-left\"><strong>Informações adicionais</strong></li>";
                echo "<li class=\"list-group-item text-left\"><strong>Rua:</strong> $rua</li>";
                echo "<li class=\"list-group-item list-group-item-secondary text-left\"><strong>Número:</strong> $numero</li>";
                echo "<li class=\"list-group-item text-left\"><strong>Complemento:</strong> $complemento</li>";
                echo "<li class=\"list-group-item list-group-item-secondary text-left\"><strong>Telefone:</strong> $telefone</li>";
                echo "</ul>";
            } else if ($status == 0 && $userId == $currentId) {
                echo "<ul class=\"list-group list-group-flush text-dark\">";
                echo "<li style=color:#2cbe2c class=\"list-group-item text-center\"><strong>Cartão de descarte em espera para coleta!</strong></li>";
                echo "<li style=color:red class=\"list-group-item\"><strong>Coleta será realizada no dia $dataColeta das $horaInicial as $horaFinal pela empresa <a href=perfil.php?user=$coletorId>$nomeColetor#$coletorId</a></strong></li>";
                echo "</ul>";
                echo "<div class='btn btn-primary mgtp' style=margin-bottom:10px onclick='systemPopup(5, $descarte)'>Cancelar</div>";
            }
        }}
        ?>
        </div>
        </div>
    </div>
</form>
    <div class="row-12">
        <div class="col adsbygoogle2">
        </div>
    </div>
</div>
</div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>