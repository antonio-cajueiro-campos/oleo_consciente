<?php include 'inc/header.php'?>
<?php $main->requiredAuthAdm(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina;?></li>
    </ol>
</nav>
	<?php
		$sql = "SELECT cd_usuario FROM tb_usuarios";
		$query = $mysqli->query($sql);
		$qtUsers = $query->num_rows;
	?>
	<div class="row">
		<div class="col-md-6">

		</div>
		<div class="col-md-6">
			<ul class="list-group list-group-flush text-dark">
				<li class="list-group-item list-group-item-secondary"><strong>Usuários cadastrados:</strong> <?php echo $qtUsers; ?></li>
				<li class="list-group-item"><strong>Gerenciar usuários</strong></li>
                <li class="list-group-item list-group-item-secondary"><strong>Gerenciar cartões de descarte</strong></li>
                <li class="list-group-item"><strong>Gerenciar banimento</strong></li>
                <li class="list-group-item list-group-item-secondary"><strong>Gerenciar advertências</strong></li>
                <li class="list-group-item"><strong>Adicionar administrador</strong></li>
            </ul>
		</div>
	</div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>

