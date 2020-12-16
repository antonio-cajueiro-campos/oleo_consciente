<?php
include_once 'main.php';
include_once 'classes/class.notify.php';
$notify = new notify();

if (isset($_POST['descartarConfirm']) && isset($_SESSION['sessao'])) {
    $currentId = $_SESSION['sessao'];
    $agendaId = $_POST['agendaId'];
    $remetenteId = $_POST['remetenteId'];
    $descarteId = $_POST['descarteId'];
    $notifyId = $_POST['notifyId'];

    $notify->criar($agendaId, $remetenteId, $currentId, $descarteId, '1');
    $sql = "UPDATE tb_descartes SET cd_status = 0, cd_agenda = '$agendaId' WHERE cd_descarte = '$descarteId'";
    mysqli_query($conectar, $sql);
    $sql = "DELETE FROM tb_notify WHERE cd_notify=$notifyId";
    mysqli_query($conectar, $sql);
    header("location: ../descarte.php?id=$descarteId");
}
if (isset($_POST['descartarCancel']) && isset($_SESSION['sessao'])) {
    $currentId = $_SESSION['sessao'];
    $agendaId = $_POST['agendaId'];
    $remetenteId = $_POST['remetenteId'];
    $descarteId = $_POST['descarteId'];
    $notifyId = $_POST['notifyId'];
    
    $notify->criar($agendaId, $remetenteId, $currentId, $descarteId, '2');
    $sql = "DELETE FROM tb_notify WHERE cd_notify=$notifyId";
    mysqli_query($conectar, $sql);
    header("location: ../descarte.php?id=$descarteId");
}

if (isset($_POST['notifyUndo']) && isset($_SESSION['sessao'])) {
    $currentId = $_SESSION['sessao'];
    $descarteId = $_POST['descarteId'];
    $sql = "DELETE FROM tb_notify WHERE cd_descarte=$descarteId";
    mysqli_query($conectar, $sql);
    $sql = "SELECT cd_notify FROM tb_notify WHERE ic_new_notify=1 AND cd_destinatario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $count = mysqli_num_rows($query);
    $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$count' WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
}
?>