<?php
    include_once 'main.php';
    include_once 'classes/class.usuario.php';
    $usuario = new usuario();

    if (isset($_POST['senha']) && isset($_SESSION['sessao'])) {
        $password = $_POST['senha'];
        $currentId = $_SESSION['sessao'];

        $result = $usuario->excluir($password, $currentId);

        switch ($result) {
            case 0: echo "msgShow(41, 1)"; break;
            case 1: echo "msgShow(59, 0, 0, 'center', '', '', false, false, true, '')"; break;
            case 2: echo "msgShow(42, 0, 0, 'center', '', '', false, false, true, '')"; break;
        }
    }
?>