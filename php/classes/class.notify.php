<?php
class notify {

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

    public function criar($agenda, $destinatario, $remetente, $descarte, $notify_type) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $data = $utils->currentDate('time');

        $sql = "SELECT MAX(cd_notify) FROM tb_notify";
        $query = $mysqli->query($sql);
        $new_idArr = $query->fetch_array(MYSQLI_NUM);
        $new_id = $new_idArr[0];

        $new_id++;

        $sql = "INSERT INTO tb_notify (cd_notify, cd_agenda, cd_destinatario, cd_remetente, cd_descarte, cd_notify_type, ic_new_notify, dt_emissao) VALUES ('$new_id', '$agenda', '$destinatario', '$remetente', '$descarte', '$notify_type', '1', '$data')";
        $mysqli->query($sql);
    }
    
    public function excluir($notifyId, $descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "DELETE FROM tb_notify WHERE cd_notify='$notifyId' AND cd_descarte='$descarteId'";
        if ($mysqli->query($sql))
        return true;
        else
        return false;        
    }

    public function consultar($descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT * FROM tb_notify WHERE cd_descarte = '$descarteId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count > 0) {
            return $query->fetch_array(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function ler($notifyId, $currentId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "UPDATE tb_notify SET ic_new_notify=0 WHERE cd_notify = '$notifyId'";
        $mysqli->query($sql);

        $sql = "SELECT cd_notify FROM tb_notify WHERE ic_new_notify=1 AND cd_destinatario = '$currentId'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;

        $sql = "UPDATE tb_usuarios SET cd_qt_notify = '$count' WHERE cd_usuario = '$currentId'";
        if ($mysqli->query($sql))
        return true;
        else
        return false;
    }
}

?>
