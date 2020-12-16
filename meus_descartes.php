<?php include 'inc/header.php'?>
<?php $main->requiredAuthDescarte(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
    <nav aria-label="breadcrumb" class="breadcrumb-main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
        </ol>
    </nav>
    <div class="cartoes text-center">
    <?php
        if (isset($_POST['byecard'])) {
            $cardId = $_POST['byecard'];
            echo "<script>;</script>";
        }

        $sqlDescarte = "SELECT cd_descarte, qt_descarte, ds_descarte, dt_criacao, cd_status FROM tb_descartes WHERE cd_usuario = '$currentId'";
        $queryDescarte = $mysqli->query($sqlDescarte);
        foreach ($queryDescarte as $descarte) {
            $descarteQt = $descarte['qt_descarte'];
            $status = $descarte['cd_status'];

            $dispo = "<span style='color:lightgreen'>Pronto para coleta</span>";

            if ($status == 0)
                $dispo = "<span style='color:red'>Em processo de descarte</span>";
            
            $data = $descarte['dt_criacao'];
            $data = inverteData($data);
            
            $descarteId = $descarte['cd_descarte'];

            echo "<div class=cardblock>";
            echo "<div onclick='systemPopup(7, $descarteId)' class='xcard'></div>";
            echo "<div class='card descartecard' style='width: 18rem;' onclick=descarteCall($descarteId)>";
            echo "<div class=card-body>";
            echo "<h5 class=card-title>DESCARTE #$descarteId</h5>";
            echo "<h6 class='card-subtitle mb-2'>";
            echo $dispo;
            echo "</h6>";
            echo "<p class='card-text'>".$descarteQt." Litros </p>";
            echo "Criado em: ".$data."<br>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "<div class=cardblock>";
        echo "<div class='card descartecard cardfim' style='width: 18rem;' onclick='systemPopup(10);'>";
        echo "<div class=card-body>";
        echo "<h5 class=card-title></h5>";
        echo "<h6 class='card-subtitle mb-2'>Adicionar cartão de descarte</h6>";
        echo "<i class='fa fa-plus add-plus' aria-hidden='true'></i>";
        echo "<br>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    ?>
    </div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>