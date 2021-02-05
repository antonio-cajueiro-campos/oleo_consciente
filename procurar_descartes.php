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
<div id="loadbox" style="display:none">
  <div id="load"></div>
  <div id="msg">Aguarde, carregando...</div>
</div>
<div class='cartoes text-center'>
<?php 
      include_once 'php/classes/class.utils.php';
      $utils = new utils();
      if (!isset($_GET['p']) && !isset($_GET['agenda'])) {
        echo "Parâmetros incorretos ";
      } else if (isset($_GET['agenda']) && !isset($_GET['p'])) {
        $agendaId = $_GET['agenda'];
        echo "<META http-equiv=refresh content=0;URL=procurar_descartes.php?agenda=$agendaId&p=0>";
      } else if (!isset($_GET['agenda']) || !ctype_digit($_GET['p'])) {
        echo "<META http-equiv=refresh content=0;URL=procurar_descartes.php?agenda=$agendaId&p=0>";
      }
      $count_total = 0;

      if (isset($_GET['agenda'])) {
        $agendaId = $_GET['agenda'];
        $pagina = isset($_GET['p']) && $_GET['p'] != "" ? $_GET['p'] : 0 ;
        $qt_por_pagina = 9;
        $pagina = $pagina * $qt_por_pagina;
        $sql = "SELECT cd_estado, cd_cidade, cd_usuario FROM tb_agendas WHERE cd_agenda = '$agendaId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count > 0) {
          $row = $query->fetch_array(MYSQLI_ASSOC);
          $verifyCurrent = $row['cd_usuario'];
          if ($verifyCurrent == $currentId) {
            $estado = $row['cd_estado'];
            $cidade = $row['cd_cidade'];

            $sql = "SELECT * FROM tb_descartes AS d JOIN tb_enderecos AS e ON d.cd_usuario = e.cd_usuario WHERE e.cd_estado = '$estado' AND e.cd_cidade = '$cidade' ORDER BY cd_descarte DESC LIMIT $pagina, $qt_por_pagina";
            $query = $mysqli->query($sql);
            $countAgenda = $query->num_rows;
            if ($countAgenda > 0) {
              foreach ($query as $descarte) {
                $usuario = $descarte['cd_usuario'];
                $estado = $descarte['cd_estado'];
                $cidade = $descarte['cd_cidade'];
                $descarteId = $descarte['cd_descarte'];
                $estado = $utils->codeToLocale($estado, 'estado');
                $cidade = $utils->codeToLocale($cidade, 'cidade');
                $descarteDesc = $descarte['ds_descarte'];
                $descarteQt = $descarte['qt_descarte'];
                $data = $descarte['dt_criacao'];
                $data = $utils->inverteData($data);
                $estado = $utils->convertUf($estado, 'reverse');
    
                $sql = "SELECT ds_bairro FROM tb_enderecos WHERE cd_usuario = '$usuario'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $descarteBairro = $row['ds_bairro'];
    
                echo "<a href=descarte.php?id=".$descarteId."&agenda=".$agendaId.">";
                echo "<div class='card b'>";
                echo "<div class=card-body>";
                echo "<h5 class=card-title>".$cidade." - ".$estado."</h5>";
                echo "<h6 class='card-subtitle mb-2'>";
                echo $descarteBairro;
                echo "</h6>";
                echo "<p class='card-text'>".$descarteQt." Litros </p>";
                echo "Criado em: ".$data."<br>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
              }
            } else {
              echo "Nenhum cartão de descarte foi encontrado para este local";
            }
            

            $sql2 = "SELECT cd_descarte FROM tb_descartes AS d JOIN tb_enderecos AS e ON d.cd_usuario = e.cd_usuario WHERE e.cd_estado = '$estado' AND e.cd_cidade = '$cidade'";
            $query2 = $mysqli->query($sql2);
            $count_total = $query2->num_rows;
            $count_total = ceil($count_total/$qt_por_pagina);

            if ($_GET['p'] > $count_total) {
              echo "<META http-equiv=refresh content=0;URL=procurar_descartes.php?agenda=$agendaId&p=".($count_total-1).">";
            } else if ($_GET['p'] < $count_total-$count_total) {
              echo "<META http-equiv=refresh content=0;URL=procurar_descartes.php?agenda=$agendaId&p=0>";
            }
          } else {
            echo "Resultado não encontrado";
          }
        } else {
          echo "Resultado não encontrado";
        }
      }
?>
</div>
<?php if (isset($_GET['p']) && $_GET['p'] != "" && $count_total != 0) { $p = $_GET['p']; ?>
<nav aria-label="...">
  <ul class="pagination">
    <?php
        if ($p == 0) {
          echo "<li class=\"page-item disabled\">";
          echo "<span class=\"page-link\">Anterior</span>";
        } else {
          echo "<li class=\"page-item\">";
          echo "<a class=\"page-link\" href=procurar_descartes.php?agenda=".$agendaId."&p=".($p-1).">Anterior</a>";
        }        
        echo "</li>";

        $inicio = $p - ceil(5 / 3);
        $limite = $p + ceil(5 / 3);

        for ($i = $inicio; $i <= $limite; $i++) {
          $ac = "";
          if ($p == $i) {
            $ac = "active";
          }
          if ($i >= 0 && $i <= $count_total-1) {
            echo "<li class=\"page-item $ac\"><a class=\"page-link\" href=\"procurar_descartes.php?agenda=$agendaId&p=".$i."\">".($i+1)."</a> ";
          }
        }

        if ($p == $count_total-1) {
          echo "<li class=\"page-item disabled\">";
          echo "<span class=\"page-link\">Próximo</span>";
        } else {
          echo "<li class=\"page-item\">";
          echo "<a class=\"page-link\" href=procurar_descartes.php?agenda=".$agendaId."&p=".($p+1).">Próximo</a>";
        }        
        echo "</li>";
    ?>
  </ul>
</nav>
<?php } ?>

</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>