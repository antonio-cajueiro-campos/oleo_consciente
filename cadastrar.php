<?php include 'inc/header.php';
    if (isset($_SESSION['sessao'])) {
        echo "<META http-equiv=refresh content=0;URL=perfil.php?user=$currentId>";
    }
?>
    <div class="container main">
    <nav aria-label="breadcrumb" class="breadcrumb-main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
        </ol>
    </nav>
        <div class="row">
            <div class="col-lg-6 desccol text-center">
                <div class="button-select" onclick="switchMode('descarte')" id="button-desc">DESCARTE</div>
            </div>
            <div class="col-lg-6 colecol text-center">
                <div class="button-select" onclick="switchMode('coleta')" id="button-cole">COLETA</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-3">
                <div class="ads"></div>
            </div>
            <div class="descarte col-lg-6" id="descarte">
                
                    <div class="row cad">
                        <div class="col-lg-12">
                            <div class="row text-center">
                                <div class="col-12">
                                <h3>CADASTRO DE DESCARTADOR</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="cadastrar.php" method="post" id="descarteForm" onsubmit="descarteCheck(); return false;">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" checked class="form-check-input pessoa-ver" id="pessoa" value="pessoa" name="tipo" onclick="switchDescarteMode('1')">
                                            <label class="form-check-label" for="pessoa">Pessoa</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input empresa-ver" id="empresa" value="empresa" name="tipo" onclick="switchDescarteMode('0')">
                                            <label class="form-check-label" for="empresa">Empresa</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="login">Nome de usuário: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="login" placeholder="Nome de usuário" maxlength="80" id="login" class="form-control" onkeyup="loginCheck('login')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="password">Digite sua senha: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="password" name="password" placeholder="Digite sua senha" maxlength="80" id="password" class="form-control" onkeyup="passCheck('password')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="confpass">Confirmar senha: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="password" name="confpass" placeholder="Confirme sua senha" maxlength="80" id="confpass" class="form-control" onkeyup="confPassCheck('password', 'confpass')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="email" id="lblemail">Digite seu e-mail: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="email" placeholder="meu@email.com" id="email" class="pessoa-email form-control" maxlength="80" onkeyup="emailCheck('email')">
                            </div>
                        </div>
                        <div class="row cad descartePessoa">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="pessoaNm" id="lblnome">Digite seu nome: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Ex: Lucas" maxlength="80" id="nome" class="pessoa-nm form-control" onkeyup="nameCheck('nome')">
                            </div>
                        </div>
                        <div class="row cad descartePessoa">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="cpf" id="lblcpf">Digite seu CPF: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="cpf" name="cpf" placeholder="000.000.000-00" class="pessoa-cd form-control" id="cpf" onkeyup="cpfCnpjCheck('cpf')">
                            </div>
                        </div>
                        <div class="row cad descarteEmpresa">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="cnpj" id="lblcnpj">Digite seu CNPJ: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" name="cnpj" placeholder="00.000.000/0000-00" maxlength="14" class="empresa-cd form-control" id="cnpj" onkeyup="cpfCnpjCheck('cnpj')">
                            </div>
                        </div>
                        <div class="row cad descartePessoa">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <select class="custom-select mr-sm-2 regiao" id="estados" name="estado">
                                        <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 topmargin">
                                <select class="custom-select mr-sm-2 regiao" id="cidades" name="cidade">
                                </select>
                            </div>
                        </div>
                        <div class="row cad bconclu">
                            <div class="col-lg-12">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success" name="cadastro-descarte" id="concluir">Concluir Cadastro</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="coleta col-lg-6" id="coleta">

                    <div class="row cad">
                        <div class="col-lg-12">
                            <div class="row text-center">
                                <div class="col-12">
                                <h3>CADASTRO DE COLETOR</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="cadastrar.php" method="post" id="coletaForm" onsubmit="coletaCheck(); return false;">
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="login2" id="lbllogin2">Nome de usuário: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" placeholder="Nome de usuário" maxlength="80" id="login2" class="form-control" onkeyup="loginCheck('login2')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="password2" id="lblpassword2">Digite sua senha: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="password" placeholder="Digite sua senha" maxlength="80" id="password2" class="form-control" onkeyup="passCheck('password2')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="confpass2" id="lblconfpass2">Confirmar senha: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="password" placeholder="Confirme sua senha" maxlength="80" id="confpass2" class="form-control" onkeyup="confPassCheck('password2', 'confpass2')">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="email3" id="lblemail3">Digite seu e-mail: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="email" placeholder="meu@email.com" id="email2" maxlength="80" onkeyup="emailCheck('email2')" class="form-control">
                            </div>
                        </div>
                        <div class="row cad">
                            <div class="col-lg-6">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <label for="login2" id="lbllogin2">Digite seu CNPJ: </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                            <input type="text" placeholder="00.000.000/0000-00" maxlength="14" id="cnpj2" onkeyup="cpfCnpjCheck('cnpj2')" class="form-control">
                            </div>
                        </div>
                        <div class="row cad bconclu">
                            <div class="col-lg-12">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <button type="submit" id="concluir2" name="cadastro-coleta" class="btn btn-success">Concluir Cadastro</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="col-3">
                <div class="ads"></div>
            </div>
        </div>
    </div>
    
    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#cpf').mask('000.000.000-00', {reverse: true});
            $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
            $('#cnpj2').mask('00.000.000/0000-00', {reverse: true});
        });
    </script>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include_once 'inc/footer.php'?>