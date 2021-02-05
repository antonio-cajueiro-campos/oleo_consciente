<?php
class descarte {

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

    public function criar($usuarioId, $descricao, $quantidade) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $dataCriacao = $utils->currentDate();

        $descricao = $mysqli->real_escape_string($descricao);
        $quantidade = $mysqli->real_escape_string($quantidade);

        $new_id = $utils->newId('tb_descartes', 'cd_descarte');

        $sql = "INSERT INTO tb_descartes (cd_descarte, cd_usuario, cd_agenda, ds_descarte, qt_descarte, cd_status, dt_criacao) VALUES ('$new_id', '$usuarioId', '0', '$descricao', '$quantidade', '1', '$dataCriacao')";

        if ($mysqli->query($sql))
            return true;
        else
            return false;
    }
    
    public function excluir($descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql= "DELETE FROM tb_descartes WHERE cd_descarte = '$descarteId'";
        if ($mysqli->query($sql))
            return true;
        else
            return false;        
    }
    // para tb_descartes
    // cd_status
    // 0 = indisponível
    // 1 = disponível
    // 2 = requisitado

    public function atualizar($descarteId, $agendaId, $descricao, $quantidade, $status) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "UPDATE tb_descartes SET cd_agenda='$agendaId', ds_descarte='$descricao', qt_descarte='$quantidade', cd_status='$status' WHERE cd_descarte = '$descarteId'";
        if ($mysqli->query($sql))
            return true;
        else
            return false;
    }

    public function removerDaAgenda($descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "UPDATE tb_descartes SET cd_agenda='0', cd_status='1' WHERE cd_descarte = '$descarteId'";
        if ($mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function buscarDescartes($agendaId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT cd_estado, cd_cidade FROM tb_agendas WHERE cd_agenda = '$agendaId'";
        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $estado = $row['cd_estado'];
        $cidade = $row['cd_cidade'];
        $sql = "SELECT cd_descarte FROM tb_descartes AS d JOIN tb_enderecos AS e ON d.cd_usuario = e.cd_usuario WHERE e.cd_estado = '$estado' AND e.cd_cidade = '$cidade' ORDER BY cd_descarte DESC LIMIT $pagina, $qt_por_pagina";
        $query = $mysqli->query($sql);
        $result = "";
        foreach ($query as $descarte) {
            $result .= $descarte['cd_descarte'];
        }
        return $result;
    }

    public function consultar($descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT * FROM tb_descartes WHERE cd_descarte = '$descarteId'";
        $query = $mysqli->query($sql);
        return $query->fetch_array(MYSQLI_ASSOC);
    }

    public function consultarWith($info, $descarteId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        switch ($info) {
            case 'dono': $sql = "SELECT cd_usuario FROM tb_descartes WHERE cd_descarte='$descarteId'"; break;
            case 'quantidade': $sql = "SELECT qt_descarte FROM tb_descartes WHERE cd_descarte='$descarteId'"; break;
            case 'exists': $sql = "SELECT cd_descarte FROM tb_descartes WHERE cd_descarte='$descarteId'"; break;
        }

        $query = $mysqli->query($sql);
        $count = $query->num_rows;

        if ($info == 'exists' && $count > 0) {
            return true;
        } else if ($info == 'exists' && $count == 0) {
            return false;
        }

        $row = $query->fetch_array(MYSQLI_NUM);
        $value = $row[0];
        return $value;

    }
}
?>
