<?php
include_once 'main.php';
if (isset($_POST['notifyUndo']) && isset($_SESSION['sessao'])) {
    $currentId = $_SESSION['sessao'];
    $notifyId = $_POST['notifyId'];
    $sql = "UPDATE tb_notify SET ic_new_notify = '0' WHERE cd_notify = '$notifyId' AND cd_destinatario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $sql = "SELECT cd_notify FROM tb_notify WHERE ic_new_notify=1 AND cd_destinatario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $count = mysqli_num_rows($query);
    $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$count' WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
}
?>