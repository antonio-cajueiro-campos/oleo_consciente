<?php include 'inc/header.php'?>
<?php $main->requiredAuth(); ?>
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
    // Coletando CPF ou CNPJ dependendo do tipo de usuário e o telefone
    $sql = "SELECT nm_usuario, ds_telefone, cd_cpf_cnpj FROM tb_usuarios WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);
    $cpf_cnpj = $row['cd_cpf_cnpj'];
    $telefone = $row['ds_telefone'];
    $nm_usuario = $row['nm_usuario'];

    // Coletando dados de premium e setando máximo  de locais de atuação
    $sql = "SELECT ic_premium, ds_atuacao FROM tb_configs WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);
    $premium = $row['ic_premium'];
    $atuacao = $row['ds_atuacao'];
  
    $qt_loc_print_estado = "";
    $qt_loc_print_cidade = "";

    if ($atuacao != "") {
        $atuacao = explode(":", $atuacao);
        $estadosToken = $atuacao[0];
        $cidadesToken = $atuacao[1];
        $estadosArr = explode("-", $estadosToken);
        $cidadesArr = explode("-", $cidadesToken);
        echo "<script type='text/javascript'>$(document).ready(function(){\n";
        for ($i = 0; $i < count($estadosArr); $i++) {
            $estado = $estadosArr[$i];
            $cidade = $cidadesArr[$i];
            echo "addAtuacao($estado, $cidade);\n";
            $estado = $utils->codeToLocale($estado, 'estado');
            $cidade = $utils->codeToLocale($cidade, 'cidade');
            $qt_loc_print_estado .= "document.getElementById('estadoAtuacao$i').value = `$estado`; $('#estadoAtuacao$i').change();";
            $qt_loc_print_cidade .= "document.getElementById('cidadeAtuacao$i').value = `$cidade`; $('#cidadeAtuacao$i').change();";
        }
        echo "});</script>";
    }
    
    // Coletando dados de login
    $sql = "SELECT nm_login, ds_email FROM tb_logins WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);

    $login = $row['nm_login'];
    $email = $row['ds_email'];

    // Coletando dados de endereço
    $sql = "SELECT cd_estado, cd_cidade, ds_bairro, ds_rua, ds_numero, ds_complemento, ds_cep FROM tb_enderecos WHERE cd_usuario = '$currentId'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);
    $estado = $row['cd_estado'];
    $cidade = $row['cd_cidade'];
    $bairro = $row['ds_bairro'];
    $rua = $row['ds_rua'];
    $numero = $row['ds_numero'];
    $complemento = $row['ds_complemento'];
    $cep = $row['ds_cep'];

    $sql = "SELECT nm_estado FROM tb_estados WHERE cd_estado = '$estado'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);
    $estado = $row['nm_estado'];
    $sql = "SELECT nm_cidade FROM tb_cidades WHERE cd_cidade = '$cidade'";
    $query = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_array($query);
    $cidade = $row['nm_cidade'];

    $cidade = $utils->cidadeView($cidade);

    if (!isset($complemento) || empty($complemento)) {
        echo "<script type='text/javascript'>
                $(document).ready(function() {
                    document.getElementById('complemento').value = ' '
                });
            </script>";
    }

    echo "<script type='text/javascript'>
    $(document).ready(function() {
        locationSet();
    });
    function locationUpdate() {
        document.getElementById('estados').value = `$estado`;
        $('#estados').change();
        document.getElementById('cidades').value = `$cidade`;
        $('#cidades').change();
        $qt_loc_print_estado
        $qt_loc_print_cidade
    }
    </script>";
?>

<ul class="nav nav-tabs configClassTab nav-justified">
    <li class="nav-item itemtab leftTab">
        <a class="nav-link itemtab active" data-toggle="tab" href="#endereco">Endereço</a>
    </li>
    <li class="nav-item itemtab">
        <a class="nav-link itemtab" data-toggle="tab" href="#loginUser" onclick="checkPassFill()">Login</a>
    </li>
    <li class="nav-item itemtab rightTab">
        <a class="nav-link itemtab" data-toggle="tab" href="#info">Informações</a>
    </li>
</ul>

<div class="contentBox configContent">
<form action="" method="post" onsubmit="updateCheck();return false;" autocomplete="off">
<div class="tab-content configClassPanel">
    <div class="tab-pane container active" id="endereco">
        <div class="row configRow">
            <div class="col-3">
                <div class="ads"></div>
            </div>
            <div class="col-lg-6">
                <div class="line configText fi">Alterar informações de endereço</div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="estados">*Seu estado: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select class="custom-select mr-sm-2 regiao" id="estados" name="estado">
                        <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="cidades">*Sua cidade: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select class="custom-select mr-sm-2 regiao" id="cidades" name="cidade">
                        </select>
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="cep">*Seu CEP: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="inputtip">
                            <input type="text" name="cep" placeholder="Ex: 00000000" maxlength="8" id="cep" class="form-control" value="<?php echo $cep; ?>" onkeyup="cepCheck()" onkeypress="return onlyCep();"  autocomplete="off">
                            <span class="tip" style="color:<?php if ($cep == "") echo "rgb(248, 157, 157)"; else echo "#ccc"; ?>;"><i data-toggle="tooltip" data-placement="top" title="Para que a coleta ocorra como o esperado, algumas informações são necessárias." class="fa fa-info-circle" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="bairro">Seu bairro: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="bairro" readonly placeholder="Ex: Vila Matias" id="bairro" class="form-control" value="<?php echo $bairro; ?>">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="rua">Sua rua: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="rua" readonly placeholder="Ex: Av Brasil" id="rua" class="form-control" value="<?php echo $rua; ?>">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="numero">*Seu número: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="inputtip">
                        <input type="text" name="numero" placeholder="Ex: 1234" maxlength="80" id="numero" class="form-control" value="<?php echo $numero; ?>" onkeypress="return onlyNum();">
                            <span class="tip" style="color:<?php if ($numero == "") echo "rgb(248, 157, 157)"; else echo "#ccc"; ?>;"><i data-toggle="tooltip" data-placement="top" title="Para que a coleta ocorra como o esperado, algumas informações são necessárias." class="fa fa-info-circle" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="complemento">Complemento: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="complemento" placeholder="Ex: Apto 20" maxlength="80" id="complemento" class="form-control" value="<?php echo $complemento; ?>">
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="ads"></div>
            </div>
        </div>
    </div>
    <div class="tab-pane container fade" id="loginUser">
        <div class="row configRow">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="line configText fi">Alterar informações de login</div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="login">Nome de usuário: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="login" placeholder="Nome de usuário" maxlength="80" id="login" class="form-control" onkeyup="loginCheck('login')" value="<?php echo $login;?>">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="email">E-mail: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="email" name="email" placeholder="meu@email.com" id="email" maxlength="80" onkeyup="emailCheck('email')" class="form-control" value="<?php echo $email;?>">
                    </div>
                </div>
                <div class="line configText">Alterar sua senha</div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="password">Digite sua senha atual: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="password" name="password" placeholder="Senha atual" maxlength="80" id="password" class="form-control" onkeyup="passCheckVerConfig('password')">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="passwordNew">Digite sua nova senha: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="password" name="passwordNew" placeholder="Nova senha" maxlength="80" id="passwordNew" class="form-control" onkeyup="passCheckVerConfig('passwordNew')">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="confpass">Confirme sua senha: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="password" name="confpass" placeholder="Confirmar senha" maxlength="80" id="confpass" class="form-control" onkeyup="confPassCheckVerConfig('passwordNew', 'confpass')">
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
    <div class="tab-pane container fade" id="info">
        <div class="row configRow">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <?php if ($tipoSelf == 1 || $tipoSelf == 3) { ?>
                <div class="line configText fi">Alterar informações pessoais de cadastro</div>
                <?php } if ($tipoSelf == 0 || $tipoSelf == 2) { ?>
                <div class="line configText fi">Alterar informações de cadastro da empresa</div>
                <?php }?>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="nome">Seu nome: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input type="text" name="nome" placeholder="Ex: Lucas" maxlength="80" id="nome" class="form-control" onkeyup="nameCheck('nome')" value="<?php echo $nm_usuario; ?>">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="telefone">Seu telefone: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="inputtip">
                        <input type="text" name="telefone" placeholder="Ex: (00) 0000-0000" maxlength="80" id="telefone" class="form-control" value="<?php echo $telefone; ?>" onkeypress="return onlyTel();">
                            <span class="tip" style="color:<?php if ($telefone == "") echo "rgb(248, 157, 157)"; else echo "#ccc"; ?>;"><i data-toggle="tooltip" data-placement="top" title="Fornecer seu telefone celular, diminui as chances de ocorrer algum imprevisto durante a coleta!" class="fa fa-info-circle" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <?php if ($tipoSelf == 1 || $tipoSelf == 3 && strlen($cpf_cnpj) == 11) { ?>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="cpf">Seu CPF: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input readonly type="text" id="cpf" class="form-control" value="<?php echo $cpf_cnpj; ?>">
                    </div>
                </div>

                <?php } if ($tipoSelf == 0 || $tipoSelf == 2 || $tipoSelf == 3 && strlen($cpf_cnpj) == 14) { ?>
                <div class="row cad">
                    <div class="col-lg-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="cnpj">Seu CNPJ: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <input readonly type="text" id="cnpj" class="form-control" value="<?php echo $cpf_cnpj; ?>">
                    </div>
                </div>

                <?php } if ($tipoSelf == 2 || $tipoSelf == 3) {  ?>
                <div class="line configText">Adicionar locais de atuação</div>
                <div class="row cad">
                    <div class="col-5 text-center mobileAtuaState lineMob">
                        <div class="line configText locale">Estados</div>
                    </div>
                    <div class="col-6 text-center mobileAtuaCity lineMob">
                        <div class="line configText locale">Cidades</div>
                    </div>
                    <div class="col-1 configLocaleCol">                        
                        <a onclick="addAtuacao()" id="addatua" class="configLocale"><i class='fa fa-plus' aria-hidden=true></i></a>
                    </div>
                </div>
                <div id="atuacoes"></div>

                <?php } ?>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
</div>
<ul class="list-group list-group-flush text-dark">
    <li class="list-group-item configSave">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-3 text-left configButtonA">
                <button type="button" onclick="systemPopup(6, <?php echo $currentId; ?>)" class="btn btn-warning">Excluir conta</button>
            </div>
            <div class="col-lg-3 text-right configButtonB">
                <input type="submit" class="btn btn-primary" value="Salvar alterações" name="salvarAlteracoes" disabled id="salvarAlteracoes">
            </div>
            <div class="col-lg-3"></div>
        </div>
    </li>
</ul>
</form>
</div>
<script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            configSaveDisabled();
            $('#cpf').mask('000.000.000-00', {reverse: true});
            $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
        });
    </script>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>
