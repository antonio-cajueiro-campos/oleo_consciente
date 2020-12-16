<?php include 'inc/header.php'?>
<?php $main->requiredAuthDescarte(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
    </ol>
</nav>
<div class="descarteInfo text-dark">
<?php
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $notifyId = $_GET['id'];

        $sql = "SELECT ic_new_notify, cd_descarte, cd_agenda, cd_remetente FROM tb_notify WHERE cd_notify = '$notifyId'";
        $query = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            $rowNotify = mysqli_fetch_array($query);
            $ic_new_notify = $rowNotify['ic_new_notify'];

            if($ic_new_notify != 0) {
                $sql = "UPDATE tb_notify SET ic_new_notify = '0' WHERE cd_notify = '$notifyId'";
                mysqli_query($conectar, $sql);
    
                $sql = "SELECT cd_qt_notify FROM tb_usuarios WHERE cd_usuario = '$currentId'";
                $query = mysqli_query($conectar, $sql);
                $row = mysqli_fetch_array($query);
                $qt_notify = $row['cd_qt_notify'];
                $qt_notify--;
                $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$qt_notify' WHERE cd_usuario = '$currentId'";
                mysqli_query($conectar, $sql);
            }
            $descarteId = $rowNotify['cd_descarte'];
            $agendaId = $rowNotify['cd_agenda'];
            $remetenteId = $rowNotify['cd_remetente'];
    
            $sql = "SELECT * FROM tb_descartes WHERE cd_descarte = '$descarteId'";
            $descarteQuery = mysqli_query($conectar, $sql);
            $count = mysqli_num_rows($descarteQuery);

            $sql = "SELECT dt_coleta, hr_inicial, hr_final, cd_usuario FROM tb_agendas WHERE cd_agenda = '$agendaId'";
            $agendaQuery = mysqli_query($conectar, $sql);
            $count2 = mysqli_num_rows($descarteQuery);
            if ($count > 0 && $count2 > 0) {
                $descarteItem = mysqli_fetch_array($descarteQuery);
                $agendaItem = mysqli_fetch_array($agendaQuery);
                $empresa = $agendaItem['cd_usuario'];
                $dataColeta = $agendaItem['dt_coleta'];
                $horaInicial = $agendaItem['hr_inicial'];
                $horaFinal = $agendaItem['hr_final'];
                $horaInicial = substr($horaInicial, 0, 5);
                $horaFinal = substr($horaFinal, 0, 5);

                $sql = "SELECT nm_usuario FROM tb_usuarios WHERE cd_usuario = '$empresa'";
                $query = mysqli_query($conectar, $sql);
                $row = mysqli_fetch_array($query);
                $empresaNome = $row['nm_usuario'];

                if ($dataColeta != null) {
                    $descarteId = $descarteItem['cd_descarte'];
                    $usuarioId = $descarteItem['cd_usuario'];
                    $descarteDesc = $descarteItem['ds_descarte'];
                    $descarteQt = $descarteItem['qt_descarte'];
                    $dataColeta = inverteData($dataColeta);
                    echo "Caro $nome_usuario a empresa <a style=text-decoration:underline href=perfil.php?user=$empresa>$empresaNome#$empresa</a> está solicitando uma coleta do seu descarte#$descarteId no dia $dataColeta das $horaInicial às $horaFinal, você aceita agendar essa coleta?";
                    echo "<form action='php/verificar_coleta.php' method=\"post\">
                    <input type=hidden name=agendaId value=$agendaId>
                    <input type=hidden name=remetenteId value=$remetenteId>
                    <input type=hidden name=descarteId value=$descarteId>
                    <input type=hidden name=notifyId value=$notifyId>
                    <input type=\"submit\" name=descartarConfirm class='btn btn-success' value=Confirmar>
                    <input type=\"submit\" name=descartarCancel class='btn btn-warning' value=Recusar>
                    </form>";
                } else {
                    echo "coleta em andamento";
                }
            } else {
                echo "descarte não encontrado";
            }
        } else {
            echo "notificação de coleta não encontrada";
        }
    } else {
        echo "Erro ao carregar página, ID do post não foi encontrado";
    }
?>
</div>

</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>