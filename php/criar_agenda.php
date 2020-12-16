<?php
include_once 'main.php';
include_once 'classes/class.agenda.php';
$agenda = new agenda();

if (isset($_POST['criar']) && isset($_SESSION['sessao'])) {
    $currentId = $_SESSION['sessao'];

    if ($agenda->criar($currentId)) {
        echo "msgShow(46,1)";
    }
}

?>
