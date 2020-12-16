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
        if (isset($_SESSION['sessao'])) {
            echo "<META http-equiv=refresh content=0;URL=index.php>";
        }
    ?>
    <form action="php/email.php" method="post">
        <div class="row">
            <div class="col-3">
                <div class="adsbygoogle"></div>
            </div>
            <div class="col-md-6">
                <div class="row cad">
                    <div class="col-md-6">
                        <div class="row text-left">
                            <div class="col-12">
                                <label for="recInfo">Digite seu E-Mail, CPF ou CNPJ: </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="recInfo" id="recInfo" placeholder="E-Mail, CPF ou CNPJ" class="form-control">
                    </div>
                </div>
                <div class="row cad">
                    <div class="col-md-12">
                        <div class="row text-center">
                            <div class="col-12">
                                <input type="submit" value="Verificar" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="adsbygoogle"></div>
            </div>
        </div>
    </form>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>