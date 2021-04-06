<?php include 'inc/header.php'?>
<?php $main->requiredAuthColeta(); ?>
<!-- ========== Conteúdo aqui ========== -->
<div class="container main">
<nav aria-label="breadcrumb" class="breadcrumb-main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="breadcrum-a" href="index.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $nome_pagina."#";?><?= isset($_GET['id']) || $_GET['id'] != "" ? $_GET['id'] : "Erro" ;?></li>
    </ol>
</nav>
<div class="container contentBox agendaConfiguration">
<?php
include_once 'php/classes/class.utils.php';
$utils = new utils();
if (isset($_GET['id'])){
    $agendaId = $_GET['id'];
    
    $dataAtual = currentDate();
    $start = new DateTimeImmutable($dataAtual);
    $datetime = $start->modify('+1 day');
    $dataAtual = $datetime->format('Y-m-d');
    $datetime = $start->modify('next week');
    $dataProxima = $datetime->format('Y-m-d');
    
    $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_agenda = '$agendaId'";
    $query = mysqli_query($conectar, $sql);
    $count = mysqli_num_rows($query);
    if ($count == 0) {
        if (isset($_POST['salvarAgenda'])) {
            if (!isset($_POST['agenda']) || $_POST['agenda'] == "") {
                echo "<script>
                var data = 'msgShow(55,2,0,\"center\",\"Antes disso...\",\"\",false,false,false)';
                localStorage.setItem('msgHtml', data);
                </script>";
            } else {
                $agenda = $_POST['agenda'];
                $max = $_POST['max'];
                $data = $_POST['data'];
                $hrInicial = $_POST['hrInicial'];
                $hrFinal = $_POST['hrFinal'];
                $localidade = explode("-", $agenda);
                $estado = $localidade[0];
                $cidade = $localidade[1];
                if (!empty($data)) {
                    if (!empty($hrInicial) && !empty($hrFinal)) {
    
                        $sql = "UPDATE tb_agendas SET cd_estado = '$estado', cd_cidade = '$cidade', dt_coleta = '$data', hr_inicial = '$hrInicial', hr_final = '$hrFinal', qt_max = '$max' WHERE cd_agenda = '$agendaId' AND cd_usuario = '$currentId'";
                        mysqli_query($conectar, $sql);
    
                        echo "<script>
                        var data = 'msgShow(50, 1)';
                        localStorage.setItem('msgHtml', data);
                        </script>";
                        echo "<META http-equiv=refresh content=0;URL=agenda.php?id=$agendaId>";
    
                    } else {
                        echo "<script>
                        var data = 'msgShow(48, 0)';
                        localStorage.setItem('msgHtml', data);
                        </script>";
                    }
                } else {
                    echo "<script>
                    var data = 'msgShow(49, 0)';
                    localStorage.setItem('msgHtml', data);
                    </script>";
                }
            }
        }

        $sql = "SELECT cd_agenda, cd_estado, cd_cidade, dt_coleta, hr_inicial, hr_final FROM tb_agendas WHERE cd_usuario = '$currentId' AND cd_agenda = '$agendaId'";
        $query = mysqli_query($conectar, $sql);
        $count = mysqli_num_rows($query);
        if ($count > 0) {
            $row = mysqli_fetch_array($query);
        ?>
            <form action="" method="post">
                <div class="row spaceAgendaConfig">
                <div class="col-lg-2"></div>
                    <div class="col-lg-4">
                        <div class="row-12">
                            <label for="agenda">Local que será efetuado a coleta: </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row-12 text-dark">
                            <select class="custom-select mr-sm-2 regiao" id="agenda" name="agenda">
                                <?php
                                    $sql = "SELECT ds_atuacao FROM tb_configs WHERE cd_usuario = '$currentId'";
                                    $query = $mysqli->query($sql);
                                    $row = $query->fetch_array(MYSQLI_ASSOC);
                                    $atuacao = $row['ds_atuacao'];

                                    if ($atuacao != "") {
                                        $atuacao = explode(":", $atuacao);
                                        $estadosToken = $atuacao[0];
                                        $cidadesToken = $atuacao[1];
                                        $estadosArr = explode("-", $estadosToken);
                                        $cidadesArr = explode("-", $cidadesToken);
                                
                                        for ($i = 0; $i < count($estadosArr); $i++) {
                                            $estadoId = $estadosArr[$i];
                                            $cidadeId = $cidadesArr[$i];
                                            $estado = $utils->codeToLocale($estadoId, 'estado');
                                            $cidade = $utils->codeToLocale($cidadeId, 'cidade');
                                            echo "<option value=\"$estadoId-$cidadeId\">$estado - $cidade</option>";
                                        }
                                    } else {
                                        echo "<option value=></option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row spaceAgendaConfig">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-4">
                        <div class="row-12">
                            <label for="data">Dia de coleta da agenda:</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row-12 text-dark">
                            <input required type="date" name="data" id="data" class="form-control" value='<?php echo $dataProxima;?>' min="<?php echo $dataAtual;?>">
                        </div>
                    </div>
                </div>
                <div class="row spaceAgendaConfig">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-4">
                        <div class="row-12">
                            <label>Selecione a hora da coleta:</label>
                        </div>
                    </div>
                    <div class="col-lg-4 horasAgendaConfig text-center">
                        <div class="row hrajustespace">
                            <div class="col-6">
                                <label for="das">Das:&nbsp;&nbsp;</label>
                                <select required  class='form-control hrAgenda' name="hrInicial" id="das">
                                    <option value="06:00:00">06:00</option>
                                    <option value="08:00:00">08:00</option>
                                    <option value="10:00:00">10:00</option>
                                    <option value="12:00:00">12:00</option>
                                    <option value="14:00:00">14:00</option>
                                    <option value="16:00:00">16:00</option>
                                    <option value="18:00:00">18:00</option>
                                    <option value="20:00:00">20:00</option>
                                </select>    
                            </div>
                            <div class="col-6">
                                <label for="as">&nbsp;&nbsp;Às:&nbsp;&nbsp;</label>
                                <select required class='form-control hrAgenda' name="hrFinal" id="as">
                                    <option value="08:00:00">08:00</option>
                                    <option value="10:00:00">10:00</option>
                                    <option value="12:00:00">12:00</option>
                                    <option value="14:00:00">14:00</option>
                                    <option value="16:00:00">16:00</option>
                                    <option value="18:00:00">18:00</option>
                                    <option value="20:00:00">20:00</option>
                                    <option value="22:00:00">22:00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row spaceAgendaConfig">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-4">
                        <div class="row-12">
                            <label>Quantidade máxima de cartões:</label>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <select required class='form-control' name="max" id="">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                </div>
                <div class="row-12 spaceAgendaConfig">
                    <div class="col text-center">
                        <input type="submit" value="Salvar configurações" name="salvarAgenda" class="btn btn-success">
                    </div>
                </div>
            </form>
        <?php
        } else {
            echo "Você não possui esta agenda.";
        }
    } else {
        echo "Ação bloqueada, esvazie a agenda antes para poder configurar";
    }
} else {
    echo "Agenda não selecionada";
}
?>
</div>
</div>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>
