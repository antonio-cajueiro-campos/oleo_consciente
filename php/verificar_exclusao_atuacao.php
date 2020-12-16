<?php
    include_once 'main.php';
    if (isset($_POST['rmvAtuacao']) && isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $rmvAtuacao = $_POST['rmvAtuacao'];
        $locale = explode("-", $rmvAtuacao);
        $estado = $locale[0];
        $cidade = $locale[1];

        $sql = "SELECT cd_agenda FROM tb_agendas WHERE cd_estado = '$estado' AND cd_cidade = '$cidade' AND cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count > 0) {
            //impedir de deletar
            echo "msgShow(66,2,0,\"center\",\"Atuação ocupada!\",\"\",false,false,false)";
        } else {
            //permitir deletar
            echo "1";
        }
    }
?>