<?php
include_once 'main.php';
include_once 'classes/class.usuario.php';
include_once 'classes/class.descarte.php';
$usuario = new usuario();
$descarte = new descarte();
if (isset($_POST['descarte']) && isset($_SESSION['sessao'])) {
    $descarteId = $_POST['descarte'];
    $currentId = $_SESSION['sessao'];

    // SELECIONAR OS LITROS
    // SELECIONAR O ID DO DONO
    // APLICAR NA TABELA CONFIG A QUANTIDADE DE MATERIAL SOMANDO COM A ANTERIOR DE AMBOS COLETOR E DESCARTADOR
    // APAGAR descarte
    // CRIAR NOTIFICAÇÃO DE COLETA BEM SUCEDIDA PRO COLETOR 65
    // CRIAR NOTIFICAÇÃO DE AVALIAÇÃO PRO DESCARTADOR
    // ASSIM QUE DESCARTADOR AVALIAR SALVAR NO PERFIL DO COLETOR

    if ($descarte->consultarWith('exists', $descarteId)) {
        $ok = false;

        $userId = $descarte->consultarWith('dono', $descarteId);
        $quantidade = $descarte->consultarWith('quantidade', $descarteId);
    
        $material = $usuario->consultar('material', $userId);
        $material = $material + $quantidade;
    
        if ($usuario->set('material', $userId, $material)) {
            $ok = true;
        } else {
            $ok = false;
        }
    
        $material = $usuario->consultar('material', $currentId);
        $material = $material + $quantidade;
    
        if ($usuario->set('material', $currentId, $material)) {
            $ok = true;
        } else {
            $ok = false;
        }

        if ($ok) {
            if ($descarte->excluir($descarteId)) {
                echo "msgShow(65, 1)";
            } else {
                echo "msgShow(66, 0)";
            }
        }
    }
}
?>
