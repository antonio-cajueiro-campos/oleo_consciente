<?php
class utils {

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

    public function strMunicipio($str) {
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        $str = strtr($str, $mapping);
        return strtoupper($str);
    }

    public function notifyDateTime($dateTime){
        $data = explode(" ", $dateTime);
        $calen = $data[0];
        $hor = $data[1];

        $hor = explode(":", $hor);
        $hora = $hor[0];
        $minuto = $hor[1];
        $segundo = $hor[2];

        $calen = explode("-",$calen);
        $ano = $calen[0];
        $mes = $calen[1];
        $dia = $calen[2];

        $dateTime = "$dia/$mes/$ano às $hora:$minuto:$segundo";

        return $dateTime;
    }

    public function cnpjInfo($cnpj) {
        // CNPJ_TESTE: 06990590000123
        // ini_set('display_errors',0);
        $api_url = 'https://www.receitaws.com.br/v1/cnpj/'.$cnpj;
        if ($jsonEnc = file_get_contents($api_url))
            $jsonDec = json_decode($jsonEnc);
        else
            $jsonDec = (object)['status'=>'MR'];
        return $jsonDec;
    }

    function formatAdm($admName) {
        if (strlen($admName) > 6) {
            $admName = substr($admName, 0, 6);
            $admName .= "...";
        }
        return "<strong><span class=adm>".strtoupper($admName)." <sup style=font-size:8px>[ADM]</span></sup></strong>";
    }

    function hashnator($path) {
        $hash = hash_file("md5", $path);
        $newpath = $path."?v=".$hash;
        return $newpath;
    }

    public function crypts($senha) {
        return hash('sha3-256', $senha);
    }

    public function currentDate($time = "") {
        $gmt = -3;
        if ($time == 'time') {
            return gmdate("Y-m-d H:i:s", time() + 3600*($gmt+date("I")));
        } else {
            return gmdate("Y-m-d", time() + 3600*($gmt+date("I")));
        }
    }

    public function convertUf($estado, $mode) {
        $stateUf = array(
            'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá', 'AM'=>'Amazonas', 'BA'=>'Bahia', 'CE'=>'Ceará', 'DF'=>'Distrito Federal', 'ES'=>'Espírito Santo',
            'GO'=>'Goiás', 'MA'=>'Maranhão', 'MT'=>'Mato Grosso', 'MS'=>'Mato Grosso do Sul', 'MG'=>'Minas Gerais', 'PA'=>'Pará', 'PB'=>'Paraíba',
            'PR'=>'Paraná', 'PE'=>'Pernambuco', 'PI'=>'Piauí', 'RJ'=>'Rio de Janeiro', 'RN'=>'Rio Grande do Norte', 'RS'=>'Rio Grande do Sul',
            'RO'=>'Rondônia', 'RR'=>'Roraima', 'SC'=>'Santa Catarina', 'SP'=>'São Paulo', 'SE'=>'Sergipe', 'TO'=>'Tocantins'
        );

        switch ($mode) {
            case 'normal': return $stateUf[$estado];
            case 'reverse': return array_search($estado, $stateUf);
        }
    }

    public function autoNamePage($nome_pagina) {
        $nome_pagina = basename($nome_pagina);
        $nome_pagina = pathinfo($nome_pagina, PATHINFO_FILENAME);
        $nome_pagina = str_replace("_", " ", $nome_pagina);
        $nome_pagina = ucwords($nome_pagina);

        $nomePalavra = explode(" ", $nome_pagina);
        $ct=0;
        foreach($nomePalavra as $palavra) {
            $ct++;
            if (strlen($palavra) == 2 || strlen($palavra) == 3) {
                $nomePalavra[$ct-1] = strtolower($palavra);
            }
        }
        return implode(" ", $nomePalavra);
    }

    public function newId($tabela, $coluna) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT MAX($coluna) FROM $tabela";
        $query = $mysqli->query($sql);
        $new_idArr = $query->fetch_array(MYSQLI_NUM);
        $new_id = $new_idArr[0];
        $new_id++;
        return $new_id;
    }

    public function inverteData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    public function localeToCode($locale, $type) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        switch ($type) {
            case 'estado': $sql = "SELECT cd_estado FROM tb_estados WHERE nm_estado = '$locale'"; break;
            case 'cidade': $sql = "SELECT cd_cidade FROM tb_cidades WHERE nm_cidade = '$locale'"; break;
        }

        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_NUM);
        return $row[0];
    }

    public function codeToLocale($code, $type) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        switch ($type) {
            case 'estado': $sql = "SELECT nm_estado FROM tb_estados WHERE cd_estado = '$code'"; break;
            case 'cidade': $sql = "SELECT nm_cidade FROM tb_cidades WHERE cd_cidade = '$code'"; break;
        }

        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_NUM);
        return $row[0];
    }

    public function cidadeView($cidade) {
        $cidade = mb_convert_case($cidade, MB_CASE_LOWER, "UTF-8");
        $cidade = str_replace("-", " ", $cidade);
        $cidade = ucwords($cidade);

        $cidadePalavra = explode(" ", $cidade);
        $ct=0;
        foreach($cidadePalavra as $palavra) {
            $ct++;
            if ($palavra == 'Da' || $palavra == 'De' || $palavra == 'E' || $palavra == 'Dos' || $palavra == 'Das' || $palavra == 'Do') {
                $cidadePalavra[$ct-1] = strtolower($palavra);
            }
        }
        $cidade = implode(" ", $cidadePalavra);
        return $cidade;
    }

    public function convertBadTags($id){
        $error = false;
        $badwords = array("" => "");
        $collectorApi = "https://pastebin.com/raw/";
        if (!file_exists("$id")) {
            if (!copy($collectorApi.$id, "$id")) {
                $error = true;
            } else {
                $badwords = (array) include "$id";
                unlink("$id");
            }
        }
        return array(
            "list" => $badwords,
            "error" => $error
        );
    }
    
    // metodo pra verificar se tem uma badword numa string, retorna um array contendo true or false, e a string convertida com asteriscos
    public function verifyBadWords($words) {
        $words = mb_convert_case($words, MB_CASE_LOWER, "UTF-8");
        $isBad = false;
        $badwords = $this->convertBadTags('U2jy55Zv');
        $error = $badwords['error'];
        if ($error == false) {
            for ($i=0; $i < count($badwords['list']); $i++) {
                $pos = strpos($words, $badwords['list'][$i]);
                while($pos !== false) {
                    for($j = $pos; $j < $pos + strlen($badwords['list'][$i]); $j++)
                        $words[$j]="*";
                    $isBad = true;
                    $pos = strpos($words, $badwords['list'][$i]);
                }
            }
        }
        return array(
            "words" => $words,
            "bad" => $isBad,
            "error" => $error
        );
    }
}   
?>
