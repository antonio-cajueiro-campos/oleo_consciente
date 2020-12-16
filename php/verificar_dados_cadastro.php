<?php
    include_once 'main.php';
    include_once 'classes/class.usuario.php';
    $usuario = new usuario();
    if (isset($_POST['enviar'])) {        
        //tipo de user pra todos os tipos
        $tipo   = $_POST['tipo'];
        $login  = $_POST['login'];
        $email  = $_POST['email'];
        $senha  = $_POST['senha'];
        $cpf_cnpj   = $_POST['cpf_cnpj'];

        //dados de user
        $nome   = $_POST['nome'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];        

        $isNotEmpty = false;
        $return = false;
        
        if (isset($tipo) && $tipo != null && $tipo != 'undefined' &&
            isset($login) && !empty($login) && $login != null && $login != 'undefined' &&
            isset($email) && !empty($email) && $email != null && $email != 'undefined' &&
            isset($senha) && !empty($senha) && $senha != null && $senha != 'undefined' &&
            isset($cpf_cnpj) && !empty($cpf_cnpj) && $cpf_cnpj != null && $cpf_cnpj != 'undefined' &&
            isset($nome) && !empty($nome) && $nome != null && $nome != 'undefined' &&
            isset($estado) && !empty($estado) && $estado != null && $estado != 'undefined' &&
            isset($cidade) && !empty($cidade) && $cidade != null && $cidade != 'undefined') {
            $isNotEmpty = true;
        } else {
            echo "msgShow(1)";
        }

        //ID: 0 Descartador Empresa
        //ID: 1 Descartador Pessoa
        //ID: 2 Coletor Empresa

        if ($isNotEmpty) {
            $result = $usuario->cadastrar($login, $email, $senha, $tipo, $nome, $cpf_cnpj, $estado, $cidade);
            $resultCode = $result['code'];
            $return = true;
        }
        if ($return) {
            switch ($resultCode) {
                case 0: $currentId = $result['currentId']; echo "msgShow(15,1,1900);//<id>$currentId</id>"; $usuario->logar($email, $senha); break;
                case 1: echo "msgShow(0,0);"; break;
                case 2: echo "msgShow(13,0);"; break;
                case 3: echo "msgShow(6,0);"; break;
                case 4: echo "msgShow(14,0,8500);"; break;
                case 5: echo "msgShow(5,0);"; break;
                case 6: echo "msgShow(28,0);"; break;
                case 7: echo "msgShow(60,0);"; break;
            }
        }
    }
?>
