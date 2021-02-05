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
            $jsonDec = (object) [ 'status' => 'MR' ];
        return $jsonDec;
    }

    public function formatAdm($admName) {
        if (strlen($admName) > 6) {
            $admName = substr($admName, 0, 6);
            $admName .= "...";
        }
        return "<strong><span class=adm>".strtoupper($admName)." <sup style=font-size:8px>[ADM]</span></sup></strong>";
    }

    public function hashnator($path) {
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

    public function getStarsImage($reviewStars) {

        $stars = "";
        
        $starsFull = "<i class='fa fa-star' aria-hidden=true></i>";
        //$starsHalf = "<i class='fa fa-star-half-o' aria-hidden=true></i>";
        $starsEmpty = "<i class='fa fa-star-o' aria-hidden=true></i>";

        $reviewStars = round($reviewStars);

        switch ($reviewStars) {
            case 0:
                for ($i = 0; $i <= 4; $i++) {
                    $stars .= $starsEmpty;
                }
            break;
            case 1:
                $stars .= $starsFull;
                for ($i = 0; $i <= 3; $i++) {
                    $stars .= $starsEmpty;
                }
            break;
            case 2:
                for ($i = 0; $i <= 1; $i++) {
                    $stars .= $starsFull;
                }
                for ($i = 0; $i <= 2; $i++) {
                    $stars .= $starsEmpty;
                }
            break;
            case 3:
                for ($i = 0; $i <= 2; $i++) {
                    $stars .= $starsFull;
                }
                for ($i = 0; $i <= 1; $i++) {
                    $stars .= $starsEmpty;
                }
            break;
            case 4:
                for ($i = 0; $i <= 3; $i++) {
                    $stars .= $starsFull;
                }
                $stars .= $starsEmpty;
            break;
            case 5:
                for ($i = 0; $i <= 4; $i++) {
                    $stars .= $starsFull;
                }
            break;
        }

        return $stars;
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
        if (count(explode("/", $data)) > 1){
            return implode("-", array_reverse(explode("/", $data)));
        } else if (count(explode("-", $data)) > 1){
            return implode("/", array_reverse(explode("-", $data)));
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

    public function organizeMaterial($material) {

        $material_agua_raw = $material * 25000;
        $matLen = strlen($material_agua_raw);


        if ($matLen < 4) {
            $material_agua = $material_agua_raw;
        } else if ($matLen >= 4 && $matLen < 7) {
            $material_agua = substr($material_agua_raw, 0, -3)." mil de ";
        } else if ($matLen >= 7 && $matLen < 10) {
            $material_agua = substr($material_agua_raw, 0, -6)." mi de ";
        } else if ($matLen >= 10 && $matLen < 13) {
            $material_agua = substr($material_agua_raw, 0, -9)." bi de ";
        } else {
            $material_agua = " muitos ";
        }

        return $material_agua;
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

    public function badwordsVer($words) {
        $path = "vendor/autoload.php";
        if (!file_exists($path)) {
            $path = "php/".$path;
            if (!file_exists($path)) {
                $path = "../vendor/autoload.php";
            }
        }
        include $path;
        $extra = [
            'badwords' => [' cu ', 'cu ', 'puta ', ' puta '],
            'ignored'  => ['cadela'],
        ];
        $verifyBW = \Badwords\Badwords::verify($words, $extra);
        if ($verifyBW) {
            return true;
        } else {
            return false;
        }
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

        $path = "filters/badwords.php";
        if (!file_exists($path)) {
            $path = "php/".$path;
            if (!file_exists($path)) {
                $path = "../filters/badwords.php";
            }
        }
        
        $badwords = (array) include $path;

        for ($i=0; $i < count($badwords); $i++) {
            $pos = strpos($words, $badwords[$i]);
            while($pos !== false) {
                for($j = $pos; $j < $pos + strlen($badwords[$i]); $j++)
                    $words[$j]="*";
                $isBad = true;
                $pos = strpos($words, $badwords[$i]);
            }
        }

        return [
            "words" => $words,
            "bad" => $isBad,
            "error" => false
        ];
    }
}
?>
