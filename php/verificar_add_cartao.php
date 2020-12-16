<?php
    include_once 'main.php';
    include_once 'classes/class.utils.php';
    include_once 'classes/class.descarte.php';
    $utils = new utils();
    $descarte = new descarte();
    if (isset($_POST['registrarDescarte']) && isset($_SESSION['sessao'])) {
        $currentId = $_SESSION['sessao'];
        $sql = "SELECT cd_tipo FROM tb_usuarios WHERE cd_usuario = '$currentId'";
        $query = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_array($query);
        if ($row['cd_tipo'] != 2) {
            $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_usuario = '$currentId'";
            $query = mysqli_query($conectar, $sql);
            $qt_cards = mysqli_num_rows($query);            
            $descricao = $_POST['descricao'];
            $quantidade = $_POST['saida'];
            if ($qt_cards < 11) {
                if ($quantidade != 0) {
                    $sql = "SELECT cd_estado, cd_cidade, ds_cep FROM tb_enderecos WHERE cd_usuario = '$currentId'";
                    $query = mysqli_query($conectar, $sql);
                    $row = mysqli_fetch_array($query);
                    $cep = $row['ds_cep'];
                    $estado = $row['cd_estado'];
                    $cidade = $row['cd_cidade'];
                    if ($cep != null && $cep != "") {
                        $infoArr = $utils->verifyBadWords($descricao);
                        $descricao = $infoArr['words'];
                        $descricaoVerify = $infoArr['bad'];
                        $errorVerify = $infoArr['error'];
                        if (!$errorVerify) {
                            if ($descricaoVerify) {
                                echo "msgShow(29,0,10000); playNotify();";
                                $sqlAdv = "SELECT qt_advertence FROM tb_usuarios WHERE cd_usuario = '$currentId'";
                                $queryAdv = mysqli_query($conectar, $sqlAdv);
                                $rowAdv = mysqli_fetch_array($queryAdv);
                                $adv = $rowAdv['qt_advertence'];
                                $adv++;
                                $sqlAdv = "UPDATE tb_usuarios SET qt_advertence = '$adv' WHERE cd_usuario = '$currentId'";
                                mysqli_query($conectar, $sqlAdv);
                            }
                            $result = $descarte->criar($currentId, $descricao, $quantidade);
                            if ($result) {
                                if (!$descricaoVerify) echo "msgShow(17,1);";
                            } else {
                                if (!$descricaoVerify) echo "msgShow(18,0);";
                            }
                        } else {
                            echo "msgShow(60,0);";
                        }
                    } else {
                        echo "msgShow(30,2,0,'center','','',false,false,false);";
                    }
                } else {
                    echo "msgShow(20,0);";
                }
            } else {
                echo "msgShow(45,0);";
            }
        } else {
            echo "msgShow(100,0);";
        }
    }
?>