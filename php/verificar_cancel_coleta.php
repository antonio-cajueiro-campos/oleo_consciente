<?php
    include_once 'main.php';
    include_once 'classes/class.descarte.php';
    include_once 'classes/class.notify.php';
    include_once 'classes/class.agenda.php';
    $descarte = new descarte();
    $notify = new notify();
    $agenda = new agenda();
    
    if (isset($_POST['cancelColeta']) && isset($_SESSION['sessao'])) {
        $descarteId = $_POST['descarteId'];
        $currentId = $_SESSION['sessao'];

        $row = $descarte->consultar($descarteId);
        $descricao = $row['ds_descarte'];
        $quantidade = $row['qt_descarte'];
        $agendaId = $row['cd_agenda'];

        $row = $agenda->consultar($agendaId);
        $empresaId = $row['cd_usuario'];

        // $row = $notify->consultar($descarteId);
        // $notifyId = $row['cd_notify'];
        

        if ($descarte->removerDaAgenda($descarteId)) {
            $result = $descarte->atualizar($descarteId, '0', $descricao, $quantidade, '1');
            // if ($notify->ler($notifyId, $currentId))
            // if ($notify->excluir($notifyId, $descarteId))
            // $notify->criar($agendaId, $empresaId, $currentId, $descarteId, '5');
        } else {
            $result = false;
        }

        if ($result)
        echo "msgShow(61, 1)";
        else
        echo "msgShow(62, 0)";
    }
?>