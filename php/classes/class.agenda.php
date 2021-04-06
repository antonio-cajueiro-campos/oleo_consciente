<?php
class agenda {

    private $ht = "";
    private $lg = "";
    private $pw = "";
    private $db = "";

    public function __construct() {
        global $system;
        $this->ht = $system->getConnection('ht');
        $this->lg = $system->getConnection('lg');
        $this->pw = $system->getConnection('pw');
        $this->db = $system->getConnection('db');
    }

    public function criar($currentId) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $new_id = $utils->newId('tb_agendas', 'cd_agenda');

        $sql = "SELECT cd_agenda FROM tb_agendas WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;

        if ($count < 7) {
            $sql = "INSERT INTO tb_agendas (cd_agenda, cd_usuario, qt_max) VALUES ('$new_id', '$currentId', 0)";
            if ($mysqli->query($sql))
                return true;
            else
                return false;
        } else {
            echo "msgShow(47,2)";
        }
    }

    public function adicionar($descarteId, $agendaId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "UPDATE tb_descartes SET cd_status = 0, cd_agenda = '$agendaId' WHERE cd_descarte = '$descarteId'";
        if (!$mysqli->query($sql)) return false; else return true;

    }
    
    public function excluir($id, $confpass) {
        
    }

    public function atualizar($descarteId, $agendaId, $descricao, $quantidade, $max) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $max = $mysqli->real_escape_string($max);
        $max = $mysqli->real_escape_string($max);
        $sql = "UPDATE tb_agendas SET cd_agenda='$agendaId', ds_descarte='$descricao', qt_descarte='$quantidade', qt_max = '$max' WHERE cd_descarte = '$descarteId'";
        if ($mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function consultarDescartes($agendaId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_agenda = '$agendaId'";
        return $mysqli->query($sql);
    }

    public function consultar($agendaId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT * FROM tb_agendas WHERE cd_agenda = '$agendaId'";
        if ($query = $mysqli->query($sql)) {
            return $query->fetch_array(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function consultarDescartesCidade($cidadeId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT descar.cd_descarte FROM tb_descartes as descar
        JOIN tb_enderecos as addr ON descar.cd_usuario = addr.cd_usuario
        WHERE cd_cidade = '$cidadeId'";
        return $mysqli->query($sql);
    }
}
?>
