<?php include 'inc/header.php'?>
<?php $main->requiredAuth(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?>#<?php if (isset($_GET['id'])) { echo  $agendaId = $_GET['id']; } else { echo "error"; }?></li>
    </ol>
</nav>

<?php
    include_once 'php/classes/class.utils.php';
    $utils = new utils();
    if (isset($_GET['id'])) {
        $agendaId = $_GET['id'];

        $sql = "SELECT cd_agenda, cd_usuario, hr_inicial, hr_final, cd_estado, cd_cidade, dt_coleta, qt_max FROM tb_agendas WHERE cd_agenda = '$agendaId'";
        $query = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            $row = mysqli_fetch_array($query);
            $agendaEmpresa = $row['cd_usuario'];
            $horaInicial = $row['hr_inicial'];
            $horaFinal = $row['hr_final'];
            $horaInicial = substr($horaInicial, 0, 5);
            $horaFinal = substr($horaFinal, 0, 5);
            $estado = $row['cd_estado'];
            $cidadeDb = $row['cd_cidade'];
            $dataColeta = $row['dt_coleta'];
            $max = $row['qt_max'];
            $dataColeta = $utils->inverteData($dataColeta);
            $cidade = $utils->codeToLocale($cidadeDb, 'cidade');
            $estado = $utils->codeToLocale($estado, 'estado');
            $estado = $utils->convertUf($estado, 'reverse');
            $nomeEmpresa = $usuario->consultar('nome', $agendaEmpresa);

            if ($cidadeDb != null) {
                $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_agenda = '$agendaId'";
                $query = mysqli_query($conectar, $sql);
                $count = mysqli_num_rows($query);
                if ($count > 0) {
                    if ($agendaEmpresa != $currentId) {
                        $descartes = $usuario->consultar('descartes', $currentId);
                        $list = "";
                        foreach ($descartes as $descarte) {
                            $descarteIds = $descarte['cd_descarte'];
                            $descarteDispo = $descarte['cd_status'];
                            if ($descarteDispo == 1)
                            $list .= "<option value=$descarteIds>Descarte#$descarteIds</option>";
                        }
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'><a href=perfil.php?user=$agendaEmpresa>$nomeEmpresa</a> | <strong>$estado - $cidade</strong> | <strong>$dataColeta</strong> | <strong> das $horaInicial às $horaFinal</strong></span>";
                        echo "</span>";
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'>Adicione seus cartões de descarte a esta agenda!</span>";
                        echo "<a onclick='systemPopup(11, $agendaId, \"$list\")'><i class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i></a>";
                        echo "</span>";
                    } else {
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'>Anexe mais coletas a ela! | <strong>$estado - $cidade</strong> | <strong>$dataColeta</strong> | <strong> das $horaInicial às $horaFinal</strong> | <strong>máx. de $max cartões</strong></span>";
                        echo "<a href=procurar_descartes.php?agenda=$agendaId&p=0><i class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i></a>";
                        echo "</span>";
                    }
                    $userColetaClickableStyle = $tipoSelf == 2 ? 'userColetaClickable' : '';
                    $userColetaClickable = $tipoSelf == 2 ? 'clickable-row' : '';
                    $userColetaFunction = $tipoSelf == 2 ? "onclick" : "data-null";
                    echo "<table class=\"table table-striped table-light\">
                    <thead>
                        <tr>
                            <th scope=\"col\">#</th>
                            <th scope=\"col\">Litros</th>
                            <th scope=\"col\">Nome</th>
                            <th scope=\"col\">Bairro</th>
                        </tr>
                    </thead>
                    <tbody>";

                    foreach ($query as $descarte) {
                        $descarteId = $descarte['cd_descarte'];
                        $sql = "SELECT nm_usuario, qt_descarte, ds_bairro FROM tb_enderecos AS e JOIN tb_descartes AS d ON e.cd_usuario = d.cd_usuario JOIN tb_usuarios AS u ON d.cd_usuario = u.cd_usuario WHERE d.cd_descarte = '$descarteId'";
                        $query = $mysqli->query($sql);
                        $row = $query->fetch_array(MYSQLI_ASSOC);
                        $bairro = $row['ds_bairro'];
                        $quantidade = $row['qt_descarte'];
                        $nome = $row['nm_usuario'];                        
                        echo "<tr class='$userColetaClickable $userColetaClickableStyle' data-href='descarte.php?id=$descarteId&agenda=$agendaId' $userColetaFunction='itemColeta($descarteId, $agendaId)'>";
                            echo "<th scope=\"row\">$descarteId</th>";
                            echo "<td>$quantidade</td>";
                            echo "<td>$nome</td>";
                            echo "<td>$bairro</td>";
                            if ($agendaEmpresa == $currentId)
                            echo "<td class=rightOutAgenda><div onclick='systemPopup(9, $descarteId)'><i class=\"fa fa-sign-out rightOutAgendaItem\" aria-hidden=\"true\"></i></div></td>";
                        echo "</tr>";                        
                    }
                    echo "</tbody></table>";
                } else {
                    if ($agendaEmpresa != $currentId && $tipoSelf != 2) {
                        $descartes = $usuario->consultar('descartes', $currentId);
                        $list = "";
                        foreach ($descartes as $descarte) {
                            $descarteIds = $descarte['cd_descarte'];
                            $descarteDispo = $descarte['cd_status'];
                            if ($descarteDispo == 1)
                            $list .= "<option value=$descarteIds>Descarte#$descarteIds</option>";
                        }
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'><a href=perfil.php?user=$agendaEmpresa>$nomeEmpresa</a> | <strong>$estado - $cidade</strong> | <strong>$dataColeta</strong> | <strong> das $horaInicial às $horaFinal</strong></span>";
                        echo "</span>";
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'>Adicione seus cartões de descarte a esta agenda!</span>";
                        echo "<a onclick='systemPopup(11, $agendaId, \"$list\")'><i class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i></a>";
                        echo "</span>";
                    } else {
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'>Agenda vazia, anexe coletas a ela! | <strong>$estado - $cidade</strong> | <strong>$dataColeta</strong> | <strong> das $horaInicial às $horaFinal</strong> | <strong>máx. de $max cartões</strong></span>";
                        echo "<a href=procurar_descartes.php?agenda=$agendaId&p=0><i class='fa fa-plus rightPlusAgendaList' aria-hidden=true></i></a>";
                        echo "</span>";
                        echo "<span class='list-group-item text-dark'>";
                        echo "<span class='agendaItem disabled'>Você pode:<br>1. Solicitar recolhimento de material<br> 2. Esperar alguém entrar em sua agenda</span>";
                        echo "</span>";
                    }
                }
            } else {
                echo "<span class='list-group-item text-dark'>";
                echo "<span class='agendaItem disabled'>Agenda indisponível, configure-a antes para poder adicionar os cartões</span>";
                $sql = "SELECT ds_atuacao FROM tb_configs WHERE cd_usuario = '$currentId'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $atuacao = $row['ds_atuacao'];

                $sql = "SELECT cd_agenda FROM tb_descartes WHERE cd_agenda = '$agendaId'";
                $query = $mysqli->query($sql);
                $countDesc = $query->num_rows;

                if ($atuacao != "") {
                    if ($countDesc == 0) {
                        echo "<a href=configurar_agenda.php?id=$agendaId><i class='fa fa-cog rightCogAgendaConfig' aria-hidden=true></i></a>";
                    } else {
                        echo "<a onclick='msgShow(56,2,0,\"center\",\"Agenda ocupada!\",\"\",false,false,false)'><i class='fa fa-cog rightCogAgendaConfig' aria-hidden='true'></i></a>";
                    }
                } else {
                    echo "<a onclick='msgShow(55,2,0,\"center\",\"Antes disso...\",\"\",false,false,false)'><i class='fa fa-cog rightCogAgendaConfig' aria-hidden='true'></i></a>";
                }
                
                echo "</span>";
            }
        } else {
            echo "agenda não encontrada";
        }
    } else {
        echo "agenda não selecionada";
    }
?>

</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>
