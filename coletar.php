<?php include 'inc/header.php'?>
<?php $main->requiredAuthColeta(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
    </ol>
</nav>

<?php
    if (isset($_GET['notify']) && !empty($_GET['notify'])) {
        $notify = $_GET['notify'];

        $sql = "SELECT ic_new_notify, cd_descarte FROM tb_notify WHERE cd_notify = '$notify'";
        $query = $mysqli->query($sql);
        $rowNotify = $query->fetch_array(MYSQLI_ASSOC);
        $ic_new_notify = $rowNotify['ic_new_notify'];

        if($ic_new_notify != 0) {
            $sql = "UPDATE tb_notify SET ic_new_notify = '0' WHERE cd_notify = '$notify'";
            $mysqli->query($sql);

            $sql = "SELECT cd_qt_notify FROM tb_usuarios WHERE cd_usuario = '$currentId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $qt_notify = $row['cd_qt_notify'];
            $qt_notify--;
            $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$qt_notify' WHERE cd_usuario = '$currentId'";
            $mysqli->query($sql);
        }
            
            $descarteId = $rowNotify['cd_descarte'];

            $sql = "SELECT * FROM tb_descartes WHERE cd_descarte = '$descarteId'";
            $descarteQuery = $mysqli->query($sql);
            $descarteItem = $descarteQuery->fetch_array(MYSQLI_ASSOC);

            $descarteId = $descarteItem['cd_descarte'];
            $usuarioId = $descarteItem['cd_usuario'];
            $descarteDesc = $descarteItem['ds_descarte'];
            $descarteQt = $descarteItem['qt_descarte'];
            $icTaxa = $descarteItem['ic_taxa'];
            echo "$usuarioId";
        
    } else {
        echo "Erro ao carregar página, ID do post não foi encontrado";
    }
?>


</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>