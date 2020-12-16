<?php
    include_once 'main.php';

    if (isset($_SESSION['sessao'])) {
        $myId = $_SESSION['sessao'];
    } else {
        $myId = 0;
    }

    $sql = "SELECT cd_notify_type, ic_new_notify, cd_notify, cd_remetente, cd_descarte, dt_emissao FROM tb_notify WHERE cd_destinatario = '$myId' order by cd_notify desc";
    $query = $mysqli->query($sql);
    $rowNum = $query->num_rows;
    if ($rowNum > 0) {
        $rows = $query->fetch_array(MYSQLI_ASSOC);
        foreach ($query as $rows) {
            $type = $rows["cd_notify_type"];
            $notify = $rows["cd_notify"];
            $descarteId = $rows["cd_descarte"];
            $idUserRem = $rows['cd_remetente'];
            $data = $rows['dt_emissao'];
            $data = $utils->notifyDateTime($data);

            $sqlNm = "SELECT cd_tipo, nm_usuario FROM tb_usuarios WHERE cd_usuario = '$idUserRem'";
            $queryNm = $mysqli->query($sqlNm);
            $rowsNm = $queryNm->fetch_array(MYSQLI_ASSOC);
            $tipo = $rowsNm['cd_tipo'];
            $nome = $rowsNm['nm_usuario'];

            $nome = strlen($nome) > 25 ? substr($nome, 0, 25)."..." : $nome;

            if ($tipo == 0 || $tipo == 1 || $tipo == 2) {
                $nome = "<strong><span style='font-size:15px;'>".$nome."</span><span style='font-size:13px;'>#</span>".$idUserRem."</strong>";
            } else if ($tipo == 3) {
                $nome = "<strong><span style='font-size:15px;color:red;text-transform:uppercase;'>".$nome."<sup style=font-size:8px>[ADM]</sup></span><span style='font-size:13px;'>#</span>".$idUserRem."</strong>";
            }            

            $new = $rows["ic_new_notify"];
            switch ($type) {
                case 0: $result = "<span style='font-size:15px;'>$nome<br>Quer agendar uma coleta para seu descarte#$descarteId</span><br><small>$data</small>"; break;
                case 1: $result = "<span style='font-size:15px;'>$nome<br>Confirmou a coleta do descarte#$descarteId</span><br><small>$data</small>"; break;
                case 2: $result = "<span style='font-size:15px;'>$nome<br>Recusou a sua solicitação de coleta do descarte#$descarteId</span><br><small>$data</small>"; break;
                case 3: $result = "<strong><span style='font-size:15px;color:red;'>Atenção:</span></strong><br>Sua conta pode ser banida permanentemente.<br><small>$data</small>"; break;
                case 4: $result = "<strong><span style='font-size:15px;color:red;'>Bem-vindo!</span></strong><br>Seja bem-vindo ao sistema Óleo Consciente.<br><small>$data</small>"; break;
                case 5: $result = "<span style='font-size:15px;'>$nome<br>Cancelou a coleta da sua agenda#00</span><br><small>$data</small>"; break;
            }

            if ($new == 1) {
                $isNew = "newNotify";
            } else if ($new == 0) {
                $isNew = "";
            }

            switch ($type) {
                case 0:
                    echo "<a href='descartar.php?id=$notify' class='dropdown-item text-dark notifyItem $isNew'>$result</a>";
                break;
                case 1:
                    echo "<span onclick='systemPopup(1, $descarteId)' class='dropdown-item text-dark notifyItem coletaNotify $isNew'>$result</span>";
                break;
                case 2:
                    echo "<span onclick='systemPopup(2, $descarteId)' class='dropdown-item text-dark notifyItem coletaNotify $isNew'>$result</span>";
                break;
                case 3:
                    echo "<span class='dropdown-item text-dark notifyItem coletaNotify $isNew'>$result</span>";
                break;
                case 4:
                    echo "<span onclick='systemPopup(8, $descarteId)' class='dropdown-item text-dark notifyItem coletaNotify $isNew'>$result</span>";
                break;
                case 5:
                    echo "<span onclick='systemPopup(2, $descarteId)' class='dropdown-item text-dark notifyItem coletaNotify $isNew'>$result</span>";
                break;
            }
        }
    } else {
        echo "<span class='dropdown-item text-dark notnot'>Não há notificações</a>";
    }
?>