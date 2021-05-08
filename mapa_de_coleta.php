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
    <div class="contentBox">
        <?php 
            include_once 'php/classes/class.agenda.php';
            include_once 'php/classes/class.descarte.php';
            $agendaObj = new Agenda();
            $descarteObj = new Descarte();

            $businessAddress = $usuario->consultarEnderecoFormatado($currentId);

            $mapCanvas = "";
            $agendasOptions = "<option value=''>Selecione uma agenda</option>";
            $rotear = false;
            $addressList = [];
            $addressCidadeList = [];

            $agendaListObject = $usuario->getAgendas($currentId);

            
            foreach ($agendaListObject as $agenda) {
                $agendaId = $agenda['cd_agenda'];
                $agendaReturn = $agendaObj->consultar($agendaId);
                if ($agendaReturn['cd_cidade'] != NULL) {
                    $cidadeName = $utils->codeToLocale($agendaReturn['cd_cidade'], 'cidade');
                    $agendasOptions .= "<option value='$agendaId'>Agenda#$agendaId - $cidadeName</option>";
                }
            }


            if (isset($_GET['agendaId']) && $_GET['agendaId'] != "") {
                $agendaId = $_GET['agendaId'];
                if ($agendaReturn = $agendaObj->consultar($agendaId)) {
                    if ($agendaReturn['cd_cidade'] != NULL) {
                        $addressCity = $utils->codeToLocale($agendaReturn['cd_cidade'], 'cidade');

                        $descartesCidade = $agendaObj->consultarDescartesCidade($agendaReturn['cd_cidade']);

                        $descartes = $agendaObj->consultarDescartes($agendaId);   

                        foreach($descartesCidade as $descarteCidadeInfo) {
                            $addressList[$descarteCidadeInfo['cd_descarte']][$descarteObj->consultarLocal($descarteCidadeInfo['cd_descarte'])] = "OFF";
                        }

                        foreach($descartes as $descarteInfo) {
                            $addressList[$descarteInfo['cd_descarte']][$descarteObj->consultarLocal($descarteInfo['cd_descarte'])] = "ON";
                        }

                        $mapCanvas = "mapCanvas";

                        $script = "<script>$(document).ready(function() { initMap();";
                        $tm = 0;

                        foreach ($addressList as $addressArr) {
                            $id = array_search($addressArr, $addressList);
                            $qtd = $descarteObj->consultarWith('quantidade', $id);
                            foreach ($addressList[$id] as $address) {
                                $tm += 370;
                                $addressLocale = array_search($address, $addressList[$id]);
    
                                if ($address == "ON") {
                                    $script .= "setMarkers('$addressLocale', '$qtd litros', '$tm');";
                                    $rotear = true;
                                } else if ($address == "OFF") {
                                    $script .= "setMarkers('$addressLocale', '$qtd litros', '$tm', 'images/marker-off.png');";
                                }
                                unset($addressList[$id]);
                            }
                        }
                        echo $script .= "setPosition('$addressCity');});</script>";
                    }
                } else {
                    $agendaId = "";
                }
            } else {
                $agendaId = "";
            }
            
        ?>
        <h1 class="contentTitle">Mapa de Coletas</h1>
        <form action="" class="form-map" method="get">
            <select class="form-control agenda-map-select" name="agendaId" id="agendasL">
            <?= $agendasOptions ?>
            </select>
            <input type="submit" value="Visualizar" class="btn btn-primary">
            <button class="btn btn-outline-primary" id="rota-btn" onclick="criarRota('<?=$businessAddress?>')" <?=$mapCanvas != "" && $rotear ? "" : "disabled" ?>>Criar Rota</button>
        </form>
        <div id="mapCanvas" class="<?=$mapCanvas;?>"></div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$system->getGapi()?>"></script>
<script src="js/map.js"></script>
<script>
$(document).ready(function() {
    if (document.getElementById('agendasL')) {
		document.getElementById('agendasL').value = <?= @!isset($agendaId) || $agendaId == "" ? "''" : $agendaId; ?>;
		$('#agendasL').change();
	}
});
</script>
<!-- ========== Conteúdo termina aqui ========== -->
<?php include 'inc/footer.php'?>