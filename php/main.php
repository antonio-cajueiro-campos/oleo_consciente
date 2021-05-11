<?php
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    include_once 'classes/class.usuario.php';
    include_once 'classes/class.system.php';
    include_once 'classes/class.utils.php';
    $system = new system();
    
    //System main config
    $sys_name = "Óleo Consciente";
    $sys_host = "https://antonio.servegame.com/oil_rescue";
    $sys_email = "contato.oil@rescue.com";
    
    $db_host = "localhost";
    $db_login = "root";
    $db_password = "batatapalha";
    $db_name = "oil_rescue";
    
    //System config
    $system->setSysConfig($sys_name, $sys_host, $sys_email);
    $system->setDbConfig($db_host, $db_login, $db_password, $db_name);
    
    //TEMPORARIO
    $conectar = mysqli_connect($db_host, $db_login, $db_password, $db_name);
    if (!mysqli_set_charset($conectar, "utf8mb4")) echo "Erro no set charset";
    if (mysqli_connect_error()) echo "Erro na conexão: ".mysqli_connect_error();
    ///////////////////////////////////////////////////////////////////////////

    $utils = new utils();
    $usuario = new usuario();
    $nome_pagina = $utils->autoNamePage($_SERVER['PHP_SELF']);

    if ($nome_pagina == "Index") {
        $nome_pagina = "Início";
        $system->setPageConfig("keywords", "desc", "abstract");
    }
    if ($nome_pagina == "Configuracoes") {
        $nome_pagina = "Configurações";
    }
    session_start();

    $ht = $system->getConnection('ht');
    $lg = $system->getConnection('lg');
    $pw = $system->getConnection('pw');
	$db = $system->getConnection('db');
    $mysqli = new mysqli($ht, $lg, $pw, $db);

    // função que pega a data atual com ou sem as horas
    function currentDate($time = "") {
        $gmt = -3;
        if ($time == 'time') {
            return gmdate("Y-m-d H:i:s", time() + 3600*($gmt+date("I")));
        } else {
            return gmdate("Y-m-d", time() + 3600*($gmt+date("I")));
        }
    }

    // função que inverte a data recebida do banco de dados
    function inverteData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    class main {

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


        // função pra verificar se, quem tá acessando a página está logado
        public function requiredAuth() {
            if (!isset($_SESSION['sessao'])) {
                echo "<META http-equiv=refresh content=0;URL=index.php>";
                exit;
            }
        }
        // função pra verificar se, quem tá acessando a página está logado como ADM
        public function requiredAuthAdm() {
            $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
    
            if (!isset($_SESSION['sessao'])) {
                echo "<META http-equiv=refresh content=0;URL=index.php>";
                exit;
            } else {
                $sid = $_SESSION['sessao'];
                $sql = "SELECT cd_tipo FROM tb_usuarios WHERE cd_usuario = '$sid'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $tipo = $row['cd_tipo'];
                if ($tipo != '3') {
                    echo "<META http-equiv=refresh content=0;URL=index.php>";
                    exit;
                }
            }
        }
    
        // função pra verificar se, quem tá acessando a página está logado como usuário do tipo Coletor
        public function requiredAuthColeta() {
            $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
    
            if (!isset($_SESSION['sessao'])) {
                echo "<META http-equiv=refresh content=0;URL=index.php>";
                exit;
            } else {
                $sid = $_SESSION['sessao'];
                $sql = "SELECT cd_tipo FROM tb_usuarios WHERE cd_usuario = '$sid'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $tipo = $row['cd_tipo'];
                if ($tipo != '2' && $tipo != '3') {
                    echo "<META http-equiv=refresh content=0;URL=index.php>";
                    exit;
                }
            }
        }
    
        // função pra verificar se, quem tá acessando a página está logado como usuário do tipo Descartador
        public function requiredAuthDescarte() {
            $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
    
            if (!isset($_SESSION['sessao'])) {
                echo "<META http-equiv=refresh content=0;URL=index.php>";
                exit;
            } else {
                $sid = $_SESSION['sessao'];
                $sql = "SELECT cd_tipo FROM tb_usuarios WHERE cd_usuario = '$sid'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $tipo = $row['cd_tipo'];
                if ($tipo != '0' && $tipo != '1' && $tipo != '3') {
                    echo "<META http-equiv=refresh content=0;URL=index.php>";
                    exit;
                }
            }
        }
    }


?>
