<?php
    include_once 'main.php';
    include_once 'classes/class.usuario.php';
    include_once 'classes/class.utils.php';
    $usuario = new usuario();
    $utils = new utils();
    if (isset($_POST['enviar']) && isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $tipoSelf = $usuario->consultar('tipo', $currentId);
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordNew = $_POST['passwordNew'];
        $nome = $_POST['nome'];
        $cep = $_POST['cep'];
        $telefone = $_POST['telefone'];

        
        if (isset($_POST['estadoAtuacao'])) {
            $estadoAtuacao = json_decode($_POST['estadoAtuacao']);
            $cidadeAtuacao = json_decode($_POST['cidadeAtuacao']);
        } else {
            $estadoAtuacao = null;
            $cidadeAtuacao = null;
        }

        if (!empty($password) && !empty($passwordNew)) {
            $password = $utils->crypts($password);
            $sql = "SELECT cd_senha FROM tb_logins WHERE cd_usuario = '$currentId'";
            $query = mysqli_query($conectar, $sql);
            $row = mysqli_fetch_array($query);
            $senha = $row['cd_senha'];
            if ($senha == $password)
                $result = $usuario->atualizar($email, $passwordNew, $nome, $currentId, $cidade, $estado, $telefone, $bairro, $complemento, $numero, $rua, $cep, $login, $tipoSelf, $estadoAtuacao, $cidadeAtuacao);
            else
                $result = 4;
        } else {
            $passwordNew = "";
            $result = $usuario->atualizar($email, $passwordNew, $nome, $currentId, $cidade, $estado, $telefone, $bairro, $complemento, $numero, $rua, $cep, $login, $tipoSelf, $estadoAtuacao, $cidadeAtuacao);
        }

        switch ($result) {
            case 0: echo "msgShow(16,1)"; break;
            case 1: echo "msgShow(28,0)"; break;
            case 2: break;
            case 3: break;
            case 4: echo "msgShow(38,0)"; break;
            case 5: echo "msgShow(40,0)"; break;
            case 6: echo "msgShow(2,0)"; break;
            case 7: echo "msgShow(37,0)"; break;
            default: break;
        }
    }
?>