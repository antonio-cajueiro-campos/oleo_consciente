<?php
include_once 'main.php';
include_once 'classes/class.agenda.php';
include_once 'classes/class.descarte.php';
$agenda = new agenda();
$descarte = new descarte();
if (isset($_POST['addAgenda']) && isset($_SESSION['sessao'])) {
    $descarteId = $_POST['descarte'];
    $agendaId = $_POST['agenda'];

    $row = $agenda->consultar($agendaId);
    $max = $row['qt_max'];

    $query = $agenda->consultarDescartes($agendaId);
    $count = $query->num_rows;

    if ($count != $max) {
        $row = $descarte->consultar($descarteId);
        $status = $row['cd_status'];
        if ($status == 1) {
            if ($agenda->adicionar($descarteId, $agendaId)) {
                echo "msgShow(62, 1)";
            } else {
                echo "msgShow(100, 0)";
            }
        } else {
            echo "cartão indisponível";
        }
    } else {
        echo "msgShow(63, 0)";
    }   
}
?>
