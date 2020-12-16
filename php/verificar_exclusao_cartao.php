<?php
    include_once 'main.php';
    if (isset($_POST['removeCard']) && isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $cardId = $_POST['removeCard'];

        $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_descarte = '$cardId' AND cd_usuario = '$currentId'";
        $query = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_descarte = '$cardId' AND cd_agenda = '0'";
            $query = mysqli_query($conectar, $sql);
            $count = mysqli_num_rows($query);
            if ($count > 0) {
                $sql = "DELETE FROM tb_descartes WHERE cd_descarte=$cardId";
                mysqli_query($conectar, $sql);
                echo "msgShow(43,1,2000,'bottom-right','','',true,true,false, '$cardId')";
            } else {
                echo "msgShow(54,2,0,\"center\",\"Cartão ocupado!\",\"\",false,false,false)";
            }
        } else {
            echo "msgShow(44,0,2000,'bottom-right','','',true,true,false, '$cardId')";
        }
    }
?>