<?php include 'inc/header.php'?>
<?php $main->requiredAuthColeta(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
    </ol>
</nav>

<div class="list-group agenda">
<?php
include_once 'php/classes/class.utils.php';
$utils = new utils();
if (isset($_GET['agendaConfig'])) {
    $agenda = $_GET['agendaConfig'];
        }
        
        if (isset($_POST['cross'])) {
            $agenda = $_POST['cross'];
        }
        
        $sql = "SELECT cd_agenda, cd_cidade, cd_estado, dt_coleta FROM tb_agendas WHERE cd_usuario = '$currentId'";
        $agenda = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($agenda);
        $agendaId = 0;
        if ($count > 0) {
            echo "<span class='list-group-item text-dark'>";
            echo "<span class='agendaItem disabled'>Adicionar nova agenda</span>";
            echo "<i onclick=systemPopup(4) class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i>";
            echo "</span>";
            foreach ($agenda as $agendaItem) {
                $agendaId = $agendaItem['cd_agenda'];
                $cidadeDb = $agendaItem['cd_cidade'];
                $estadoDb = $agendaItem['cd_estado'];
                $dtColeta = $agendaItem['dt_coleta'];
                $cidade = $utils->codeToLocale($cidadeDb, 'cidade');
                $estado = $utils->codeToLocale($estadoDb, 'estado');
                $estado = $utils->convertUf($estado, 'reverse');
                if ($cidade == "") {
                    $cidade = "Estado inteiro";
                }

                $dtColeta = $utils->inverteData($dtColeta);

                $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_agenda='$agendaId'";
                $query = mysqli_query($conectar, $sql);
                $count = mysqli_num_rows($query);
                if ($count == 0 && $cidadeDb == null) {
                    $msg = "Agenda está indisponível, configure para ativá-la";
                    $status = "<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"$msg\" class='status i text-center'><i class=\"fa fa-times-circle\" aria-hidden=\"true\"></i></span>";
                } else if ($count > 0 && $cidadeDb != null) {
                    $msg = "Agenda está ocupada, esvazie-a antes para poder configurar";
                    $status = "<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"$msg\" class='status amarelo text-center'><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i></span>";
                } else if ($count == 0 && $cidadeDb != null) {
                    $msg = "Agenda está livre para ser configurada";
                    $status = "<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"$msg\" class='status verde text-center'><i class=\"fa fa-check-square\" aria-hidden=\"true\"></i></span>";
                }

                echo "<div class=agendaBlock>";
                echo "<a href='agenda.php?id=$agendaId'>";
                echo "<span class='list-group-item list-group-item-action'>";
                echo "<span class='agendaItem'>$status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"font-weight:bold;\">Agenda#$agendaId</span>";
                if ($cidadeDb == null) {
                    echo "<br><div class='line agenda'></div><span style=color:#d43333;>Agenda indisponível, configure para ativá-la</span>";
                } else {
                    echo "<br><div class='line agenda'></div><span style=color:#33d43b;>Agenda Ativa &nbsp;&nbsp;|&nbsp;&nbsp; $estado - $cidade&nbsp;&nbsp|&nbsp;&nbsp$dtColeta</span>";
                }
                echo "</span>";
                $sql = "SELECT ds_atuacao FROM tb_configs WHERE cd_usuario = '$currentId'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $atuacao = $row['ds_atuacao'];

                $sql = "SELECT cd_agenda FROM tb_descartes WHERE cd_agenda = '$agendaId'";
                $query = $mysqli->query($sql);
                $countDesc = $query->num_rows;

                if ($atuacao != "") {
                    if ($countDesc == 0) {
                        echo "<a class=agendaControl href=configurar_agenda.php?id=$agendaId><i class='fa fa-cog rightCogAgendaList' aria-hidden='true'></i></a>";
                    } else {
                        echo "<a class=agendaControl onclick='msgShow(56,2,0,\"center\",\"Agenda ocupada!\",\"\",false,false,false)'><i class='fa fa-cog rightCogAgendaList' aria-hidden='true'></i></a>";
                    }
                } else {
                    echo "<a class=agendaControl onclick='msgShow(55,2,0,\"center\",\"Antes disso...\",\"\",false,false,false)'><i class='fa fa-cog rightCogAgendaList' aria-hidden='true'></i></a>";
                }
                if ($countDesc == 0) {
                    echo "<div class=agendaControl onclick='systemPopup(3, $agendaId)'><i class='fa fa-times rightCrossAgendaList' aria-hidden=true></i></div>";
                } else {
                    echo "<div class=agendaControl onclick='msgShow(57,2,0,\"center\",\"Agenda ocupada!\",\"\",false,false,false)'><i class='fa fa-times rightCrossAgendaList' aria-hidden=true></i></div>";
                }
                echo "</span>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<span class='list-group-item text-dark'>";
            echo "<span class='agendaItem disabled'>Lista de agendas vazia, crie uma nova!</span>";
            echo "<i onclick=systemPopup(4) class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i>";
            echo "</span>";
        }
    ?>
</div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>
