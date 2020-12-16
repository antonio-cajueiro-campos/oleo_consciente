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

<div id="loadbox" style="display:none">
  <div id="load"></div>
  <div id="msg">Aguarde, carregando...</div>
</div>

<div class='cartoes text-center'>
<?php
    if (isset($_GET['p']) && $_GET['p'] != "" && ctype_digit($_GET['p'])) {
        $p = $_GET['p'];
    } else {
        $p = 0;
        echo "<META http-equiv=refresh content=0;URL=procurar_agendas.php?p=0>";
    }

    $count_total = 0;
    $qt_por_pagina = 9;
    $pagina = $p * $qt_por_pagina;

    $estado = $usuario->consultar('estado', $currentId);
    $cidade = $usuario->consultar('cidade', $currentId);
    
    $sql = "SELECT cd_agenda, cd_usuario, cd_estado, cd_cidade, dt_coleta, hr_inicial, hr_final FROM tb_agendas WHERE cd_estado = '$estado' AND cd_cidade = '$cidade' order by dt_coleta asc LIMIT $pagina, $qt_por_pagina";
    $query = $mysqli->query($sql);
  
    $sql2 = "SELECT cd_agenda FROM tb_agendas WHERE cd_estado = '$estado' AND cd_cidade = '$cidade'";

    $query2 = $mysqli->query($sql2);
    $count_total = $count = $query2->num_rows;
    $count_total = ceil($count_total/$qt_por_pagina);
    if ($p > $count_total) {
        echo "<META http-equiv=refresh content=0;URL=procurar_agendas.php?p=".($count_total-1).">";
    } else if ($pagina < $count_total-$count_total) {
        echo "<META http-equiv=refresh content=0;URL=procurar_agendas.php?p=0>";
    }

    if ($count == 0) {
      echo "Não foi encontrada nenhuma agenda de coleta perto de você";
    } else {
      foreach ($query as $agenda) {
          $empresa = $agenda['cd_usuario'];
          $estadoDb = $agenda['cd_estado'];
          $cidadeDb = $agenda['cd_cidade'];
          $agendaId = $agenda['cd_agenda'];
          $estado = $utils->codeToLocale($estadoDb, 'estado');
          $cidade = $utils->codeToLocale($cidadeDb, 'cidade');
  
          $nomeEmpresa = $usuario->consultar('nome', $empresa);
  
          $dataColeta = $agenda['dt_coleta'];
          $horaInicial = $agenda['hr_inicial'];
          $horaFinal = $agenda['hr_final'];
  
          $horaInicial = substr($horaInicial, 0, 5);
          $horaFinal = substr($horaFinal, 0, 5);
  
          $data = inverteData($dataColeta);
          $estado = $utils->convertUf($estado, 'reverse');
          if ($cidadeDb != null) {
              echo "<a href=agenda.php?id=".$agendaId.">";
              echo "<span class='list-group-item text-dark text-left'>";
              echo "<span class='agendaItem disabled'>Agenda#$agendaId |  <strong>$data</strong> | <strong> das $horaInicial às $horaFinal</strong> criado por $nomeEmpresa</span>";
              echo "</span>";
              echo "</a>";
          }
      }
    }

  
     

?>
</div>
<?php if ($count_total != 0) { ?>
<nav aria-label="...">
  <ul class="pagination">
    <?php
        if ($p == 0) {
          echo "<li class=\"page-item disabled\">";
          echo "<span class=\"page-link\">Anterior</span>";
        } else {
          echo "<li class=\"page-item\">";
          echo "<a class=\"page-link\" href=procurar_agendas.php?p=".($p-1).">Anterior</a>";
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
            echo "<li class=\"page-item $ac\"><a class=\"page-link\" href=\"procurar_agendas.php?p=".$i."\">".($i+1)."</a> ";
          }
        }

        if ($p == $count_total-1) {
          echo "<li class=\"page-item disabled\">";
          echo "<span class=\"page-link\">Próximo</span>";
        } else {
          echo "<li class=\"page-item\">";
          echo "<a class=\"page-link\" href=procurar_agendas.php?p=".($p+1).">Próximo</a>";
        }        
        echo "</li>";
    ?>
  </ul>
</nav>
<?php } ?>

</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>

