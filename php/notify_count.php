<?php
    include_once 'main.php';
    include_once 'classes/class.notify.php';
    $notify = new notify();    
    
    if (isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $sql = "SELECT cd_usuario FROM tb_usuarios WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count == 0) {
            unset($_SESSION['sessao']);
            session_destroy();
            echo "<META http-equiv=refresh content=0;URL=index.php>";
        }
        $sql = "SELECT ic_desativado FROM tb_usuarios WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $value = $row['ic_desativado'];
        if ($value == 1) {
            unset($_SESSION['sessao']);
            session_destroy();
            echo "<META http-equiv=refresh content=0;URL=index.php>";
        }
        
        $sql = "SELECT qt_advertence FROM tb_usuarios WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $advertence = $row['qt_advertence'];

        if (($advertence >= 10 && $advertence < 11) || ($advertence >= 20 && $advertence < 21)) {
            $notify->criar(0, $currentId, 0, 0, '3');

            if ($advertence == 20 && $_SESSION['cd_tipo'] != 3) {
                $sqlde = "UPDATE tb_usuarios SET ic_desativado = '1' WHERE cd_usuario = '$currentId'";
                $mysqli->query($sqlde);
            }

            $sqlAdv = "SELECT qt_advertence FROM tb_usuarios WHERE cd_usuario = '$currentId'";
            $queryAdv = $mysqli->query($sqlAdv);
            $rowAdv = $query->fetch_array(MYSQLI_ASSOC);
            $adv = $rowAdv['qt_advertence'];
            $adv++;
            $sqlAdv = "UPDATE tb_usuarios SET qt_advertence = '$adv' WHERE cd_usuario = '$currentId'";
            $mysqli->query($sqlAdv);
        }
                
        $sql = "SELECT cd_qt_notify FROM tb_usuarios WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $qt_notify = $row['cd_qt_notify'];

        $sql = "SELECT cd_notify FROM tb_notify WHERE cd_destinatario = '$currentId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;

        if ($count > 0) {
            $sql = "SELECT cd_notify FROM tb_notify WHERE ic_new_notify = '1' AND cd_destinatario = '$currentId'";
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count > $qt_notify) {
                $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$count' WHERE cd_usuario = '$currentId'";
                $mysqli->query($sql);
                echo "<script>playNotify();</script>";
                echo "<script>msgShow(25, 3);</script>";
                echo $count;
            } else if ($count == '0') {
                echo "";
            } else {
                echo $count;
            }
        }
    }
?>
