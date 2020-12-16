<?php
    include_once 'main.php';
    if (isset($_POST['agendaId']) && isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $agendaId = $_POST['agendaId'];

        $sql = "SELECT cd_agenda FROM tb_agendas WHERE cd_agenda = '$agendaId' AND cd_usuario = '$currentId'";
        $query = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            $sql = "SELECT cd_agenda FROM tb_descartes WHERE cd_agenda = '$agendaId'";
            $query = mysqli_query($conectar, $sql);
            $countDesc = mysqli_num_rows($query);
            if ($countDesc > 0) {
                echo "msgShow(57,2,0,\"center\",\"Agenda ocupada!\",\"\",false,false,false)";
            } else {
                $sql = "DELETE FROM tb_agendas WHERE cd_agenda=$agendaId";
                mysqli_query($conectar, $sql);
                echo "msgShow(51,1,2000,'bottom-right','','',true,true,false, '$agendaId')";
            }
        } else {
            echo "msgShow(52,0,2000,'bottom-right','','',true,true,false, '$agendaId')";
        }
    }
?>