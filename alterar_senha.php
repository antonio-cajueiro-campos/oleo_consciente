<?php include 'inc/header.php'?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
    <nav aria-label="breadcrumb" class="breadcrumb-main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
        </ol>
    </nav>
    <?php
        include_once 'php/classes/class.utils.php';
        $utils = new utils();
        if (!isset($_GET['token']) || !isset($_GET['userAuth'])) {
            echo "<META http-equiv=refresh content=0;URL=index.php>";
        }

        $userAuth = $_GET['userAuth'];
        $getCode = $_GET['token'];

        $sql = "SELECT cd_code FROM tb_usuarios WHERE cd_usuario ='$userAuth'";
        $query = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_array($query);
        $codigo = $row['cd_code'];
        if ($codigo == $getCode) {
    ?>
    <form method=post onsubmit="return savePassCheck();">
    <div class="col-md-6">
        <div class="row cad">
            <div class="col-md-6">
                <div class="row text-left">
                    <div class="col-12">
                        <label for="senhaN">Senha: </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <input id="senhaN" type='password' name='novasenha' placeholder='Digite sua nova senha' class="form-control" onkeyup="passCheck('senhaN')">
                <input type='hidden' name='user' value='<?php echo $userAuth; ?>'>
            </div>
        </div>
        <div class="row cad">
            <div class="col-md-6">
                <div class="row text-left">
                    <div class="col-12">
                        <label for="senhaNN">Confirme sua senha: </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <input id="senhaNN" type='password' name='novasenha2' placeholder='Confirme sua nova senha' class="form-control" onkeyup="confPassCheck('senhaN', 'senhaNN')">
            </div>
        </div>
        <div class="row cad">
            <div class="col-md-12">
                <div class="row text-center">
                    <div class="col-12">
                        <input type=submit name=alterarsenha value=Alterar class="btn btn-success">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <?php
    } else {
        echo "<script>msgShow(23,0,15000);</script>";
    }
    if (isset($_POST['alterarsenha'])) {

        $userAuth = $_POST['user'];
        $novasenha = $_POST['novasenha'];

        $novasenha = $utils->crypts($novasenha);

        $sql = "UPDATE tb_logins SET cd_senha = '$novasenha' WHERE cd_usuario = '$userAuth'";
        mysqli_query($conectar, $sql);

        $sql = "UPDATE tb_usuarios SET cd_code = null WHERE cd_usuario = '$userAuth'";
        mysqli_query($conectar, $sql);
        
        echo "<script>msgShow(24,1);</script>";
        echo "<META http-equiv=refresh content=2;URL=index.php>";
    }   
?>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>