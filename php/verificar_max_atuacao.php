<?php
    include_once 'main.php';
    include_once 'classes/class.usuario.php';
    $usuario = new usuario();
    if (isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $premium = $usuario->consultar('premium', $currentId);

        if ($premium == 0) {
            echo "4";
        } else {
            echo "9";
        }
    }
?>