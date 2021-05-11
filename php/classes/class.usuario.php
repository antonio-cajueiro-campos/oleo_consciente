<?php
class usuario {

    private $sys_host = "";
    private $ht = "";
    private $lg = "";
    private $pw = "";
    private $db = "";

    public function __construct() {
        global $system;
        $this->sys_host = $system->site_address;
        $this->ht = $system->getConnection('ht');
        $this->lg = $system->getConnection('lg');
        $this->pw = $system->getConnection('pw');
        $this->db = $system->getConnection('db');
    }

    public function cadastrar($login, $email, $senha, $tipo, $nome, $cpf_cnpj, $estado, $cidade) {
        include_once 'class.utils.php';
        include_once 'class.notify.php';
        $utils = new utils();
        $notify = new notify();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $everythingIsOk = false;
        $bairro = $rua = $numero = $complemento = $cep = $telefone = "";
    
        // limpa, organiza e higieniza as strings
        $cpf_cnpj = str_replace(".", "", $cpf_cnpj);
        $cpf_cnpj = str_replace("-", "", $cpf_cnpj);
        $cpf_cnpj = str_replace("/", "", $cpf_cnpj);
        $login = mb_convert_case($login, MB_CASE_LOWER, "UTF-8");
        $login = $mysqli->real_escape_string($login);
        $email = $mysqli->real_escape_string($email);
        $nome = $mysqli->real_escape_string($nome);
        $cpf_cnpj = $mysqli->real_escape_string($cpf_cnpj);
        $cidade = $mysqli->real_escape_string($cidade);
        $estado = $mysqli->real_escape_string($estado);
    
        $senha = $utils->crypts($senha);
        $data = $utils->currentDate();
        
        // verifica se o nome digitado tem palavrões, se sim, retorna código de erro 6
        // try {
        //     $is = $utils->verifyBadWords($nome);
        //     if ($is['error'] == true) {
        //         throw new Exception("Erro");
        //     }
        // } catch (Throwable $e) {
        //     return array( "code" => 7 );
        // }

        // if ($is['bad']) return array( "code" => 6 );

        // query pra verificar se ja existe usuario com o email ou login inserido
        $sql = "SELECT cd_usuario FROM tb_logins WHERE ds_email = '$email' OR nm_login = '$login'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count == 0) {
            // verifica se existe um usuario com o cpf ou cnpj inserido na tabela empresa de descarte
            $sql = "SELECT cd_usuario FROM tb_usuarios WHERE cd_cpf_cnpj = '$cpf_cnpj'";
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count == 0) {
                $sql = "SELECT MAX(cd_usuario) FROM tb_usuarios";
                $query = $mysqli->query($sql);
                $new_idArr = $query->fetch_array(MYSQLI_NUM);
                $new_id = $new_idArr[0];
                $new_id++;
                switch ($tipo) {
                    case 0: case 2:
                        $obj = $utils->cnpjInfo($cpf_cnpj);
                        $status = $obj->status;
                        if ($status == "OK") {
                            //$nomearr = explode(" ", $obj->nome);
                            //$nome = $nomearr[0];
                            $nome = $obj->nome;
                            $nome = mb_convert_case($nome, MB_CASE_LOWER, "UTF-8");
                            $nome = ucwords($nome);
                            $telefone = $obj->telefone;
                            $estado = $utils->convertUf($obj->uf, 'normal');
                            $cidade = $obj->municipio;
                            $bairro = $obj->bairro;
                            $rua = $obj->logradouro;
                            $numero = $obj->numero;
                            $complemento = $obj->complemento;
                            $cep = $obj->cep;
                            $cep = str_replace(".","", $cep);
                            $cep = str_replace("-","", $cep);
                            $cidade = mb_convert_case($cidade, MB_CASE_LOWER, "UTF-8");
                            $cidade = ucwords($cidade);
                            $cidade = str_replace("-", " ", $cidade);

                            $cidade = $mysqli->real_escape_string($cidade);
                            $bairro = $mysqli->real_escape_string($bairro);
                            $rua = $mysqli->real_escape_string($rua);
                            $complemento = $mysqli->real_escape_string($complemento);
       
                            $estado = $utils->localeToCode($estado, 'estado');
                            $cidade = $utils->localeToCode($cidade, 'cidade');
                            $everythingIsOk = true;
                        } else if ($status == "MR") {
                            // retorna erro 4 caso a API receba muitas requests de validação de CNPJ e retorne MR (many requests)
                            return array( "code" => 4 );
                        } else {
                            // retorna erro 2 caso o status do CNPJ não seja OK
                            return array( "code" => 2 );
                        }
                    break;
                    case 1:
                        $cidade = $utils->strMunicipio($cidade);
                        $cidade = mb_convert_case($cidade, MB_CASE_LOWER, "UTF-8");
                        $cidade = ucwords($cidade);
                        $cidade = str_replace("-", " ", $cidade);
                        $estado = $utils->localeToCode($estado, 'estado');
                        $cidade = $utils->localeToCode($cidade, 'cidade');
                        $everythingIsOk = true;
                    break;
                }

                if ($everythingIsOk) {
                    // query de inserções nas tabelas para um novo usuário
                    $sqlLogins = "INSERT INTO tb_logins (cd_usuario, nm_login, ds_email, cd_senha) VALUES ('$new_id', '$login', '$email', '$senha')";
                    $sqlUsuarios = "INSERT INTO tb_usuarios (cd_usuario, nm_usuario, ds_telefone, cd_cpf_cnpj, cd_tipo, dt_criacao, cd_qt_notify, qt_advertence, ic_desativado) VALUES ('$new_id', '$nome', '$telefone', '$cpf_cnpj', '$tipo', '$data', '0', '0', '0')";
                    $sqlEnderecos = "INSERT INTO tb_enderecos (cd_usuario, cd_estado, cd_cidade, ds_bairro, ds_rua, ds_numero, ds_complemento, ds_cep) VALUES ('$new_id', '$estado', '$cidade', '$bairro', '$rua', '$numero', '$complemento', '$cep')";
                    $sqlConfigs = "INSERT INTO tb_configs (cd_usuario, cd_theme, ic_premium, qt_material, qt_nivel, ds_atuacao, ds_xp) VALUES ('$new_id', '0', '0', '0', '1', '', '0')";
                    
                    try {
                        $mysqli->begin_transaction();
                        
                        $mysqli->query($sqlLogins);
                        $mysqli->query($sqlUsuarios);
                        $mysqli->query($sqlEnderecos);
                        $mysqli->query($sqlConfigs);
                        
                        $mysqli->commit();
                        
                        $notify->criar(0, $new_id, 0, 0, '4');
                    } catch (Throwable $e) {
                        $mysqli->rollback();
                        return array( "code" => 5 );
                    }

                    return array(
                        "code" => 0,
                        "currentId" => $new_id
                    );
                }
            } else
                return array( "code" => 3 );
        } else
            return array( "code" => 1 );
    }

    public function atualizar($email, $senha, $nome, $currentId, $cidade, $estado, $telefone, $bairro, $complemento, $numero, $rua, $cep, $login, $tipoSelf, $estadoAtuacao, $cidadeAtuacao) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $is = $utils->verifyBadWords($nome);
        if ($is['bad']) return 1;

        $nome = $mysqli->real_escape_string($nome);
        $login = $mysqli->real_escape_string($login);
        $email = $mysqli->real_escape_string($email);
        $telefone = $mysqli->real_escape_string($telefone);
        $estado = $mysqli->real_escape_string($estado);
        $cidade = $mysqli->real_escape_string($cidade);
        $bairro = $mysqli->real_escape_string($bairro);
        $rua = $mysqli->real_escape_string($rua);
        $numero = $mysqli->real_escape_string($numero);
        $complemento = $mysqli->real_escape_string($complemento);
        $cep = $mysqli->real_escape_string($cep);

        $estado = $utils->localeToCode($estado, 'estado');
        $cidade = $utils->localeToCode($cidade, 'cidade');

        if ($estadoAtuacao != null && $cidadeAtuacao != null) {
            $atuacaoEstado = "";
            $atuacaoCidade = "";
            $localeArr = array();
            for ($i = 0; $i < count($estadoAtuacao); $i++) {
                if ($estadoAtuacao[$i] != "" || $cidadeAtuacao[$i] != "") {
                    $estadoAtuacaozz = $utils->localeToCode($estadoAtuacao[$i], 'estado');
                    $cidadeAtuacaozz = $utils->localeToCode($cidadeAtuacao[$i], 'cidade');
                }
                if ($estadoAtuacaozz != 0)
                $localeArr[$cidadeAtuacaozz] = $estadoAtuacaozz;
            }

            foreach ($localeArr as $estadoX) {
                $cidadeZ = array_search($estadoX, $localeArr);
                $estadoZ = $localeArr[$cidadeZ];
                unset($localeArr[$cidadeZ]);
                $atuacaoEstado .= $estadoZ."-";
                $atuacaoCidade .= $cidadeZ."-";
            }
            
            $atuacaoEstado = rtrim($atuacaoEstado, "-");
            $atuacaoCidade = rtrim($atuacaoCidade, "-");

            $atuacao = $atuacaoEstado.":".$atuacaoCidade;
            
            $sql = "UPDATE tb_configs SET ds_atuacao = '$atuacao' WHERE cd_usuario = '$currentId'";
            $mysqli->query($sql);
        } else {
            $sql = "UPDATE tb_configs SET ds_atuacao = '' WHERE cd_usuario = '$currentId'";
            $mysqli->query($sql);
        }

        $sql = ("SELECT nm_login FROM tb_logins WHERE nm_login = '$login' AND NOT cd_usuario = '$currentId'");
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count > 0) {
            $rowArray = $query->fetch_array(MYSQLI_ASSOC);
            $rowLogin = $rowArray['nm_login']." já está sendo utilizado por outra conta.";
            echo "msgShow(34,2,0,'center','','',false,true,false, '$rowLogin');";
            return 2;
        } else {
            $sql = ("SELECT ds_email FROM tb_logins WHERE ds_email = '$email' AND NOT cd_usuario = '$currentId'");
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count > 0) {
                $rowArray = $query->fetch_array(MYSQLI_ASSOC);
                $rowEmail = $rowArray['ds_email']." já está sendo utilizado por outra conta.";
                echo "msgShow(35,2,0,'center','','',false,true,false, '$rowEmail');";
                return 3;
            } else {
                $sqlEndereco = "UPDATE tb_enderecos SET cd_estado = '$estado', cd_cidade = '$cidade', ds_bairro = '$bairro', ds_rua = '$rua', ds_numero = '$numero', ds_complemento = '$complemento', ds_cep = '$cep' WHERE cd_usuario = '$currentId'";
                $sqlLogin = "UPDATE tb_logins SET nm_login = '$login', ds_email = '$email' WHERE cd_usuario = '$currentId'";
                $sqlNome = ("UPDATE tb_usuarios SET nm_usuario = '$nome', ds_telefone = '$telefone' WHERE cd_usuario = '$currentId'");

                if ($senha != "") {	
                    $senha = $utils->crypts($senha);
                    $sqlLogin = "UPDATE tb_logins SET nm_login = '$login', ds_email = '$email', cd_senha = '$senha' WHERE cd_usuario = '$currentId'";
                }

                $mysqli->query($sqlEndereco);
                $mysqli->query($sqlLogin);
                $mysqli->query($sqlNome);
                return 0;
            }
        }
    }

    public function excluir($password, $currentId) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $isEverythingOk = true;
        
        $senhaInput = $utils->crypts($password);
        $sql = "SELECT cd_senha FROM tb_logins WHERE cd_usuario = '$currentId'";
        $query = $mysqli->query($sql);
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $senhaDb = $row['cd_senha'];

        if ($senhaDb == $senhaInput) {
            $sql = "SELECT cd_agenda FROM tb_agendas WHERE cd_usuario = '$currentId'";
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count > 0) {
                foreach ($query as $agenda) {
                    $agendaId = $agenda['cd_agenda'];
                    $sql = "SELECT cd_agenda FROM tb_descartes WHERE cd_agenda = '$agendaId'";
                    $query = $mysqli->query($sql);
                    $count = $query->num_rows;
                    if ($count > 0) {
                        $isEverythingOk = false;
                    }
                }
            }
            if ($isEverythingOk) {
                $sql = "DELETE FROM tb_logins WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_configs WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_enderecos WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_notify WHERE cd_destinatario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_agendas WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_descartes WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_atuacao_coletor WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                $sql = "DELETE FROM tb_usuarios WHERE cd_usuario=$currentId";
                $mysqli->query($sql);
                unset($_SESSION['sessao']);
                session_destroy();
                return 0;
            } else
                return 1;
        } else
            return 2;
    }

    public function set($config, $currentId, $value) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $col = $config == "xp" ? "ds_xp" : false;
        $col = $config == "tema" ? "cd_theme" : false;
        $col = $config == "nivel" ? "qt_nivel" : false;
        $col = $config == "premium" ? "ic_premium" : false;
        $col = $config == "material" ? "qt_material" : false;

        if ($col)
            $sql = "UPDATE tb_configs SET $col = '$value' WHERE cd_usuario = '$currentId'";
        
        
        if ($col && $mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getImage($currentId, $api = false) {
        include_once 'class.utils.php';
        $utils = new utils();
        $dir = "images/user_photos/$currentId/";

        if ($api)
        $dir = "../../images/user_photos/$currentId/";

        $photo = "unknown";
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..")
                    $photo = $file;
                }
                closedir($dh);
                
            }
        }
        $img = !file_exists($dir.$photo) ? "images/no-avatar.png" : $dir.$photo;

        if ($img)
        $img = $utils->hashnator($img);
        $img = str_replace("../../images", "images", $img);
        return $img;
    }

    public function getElo($currentId) {

        $lvl = $this->consultar('nivel', $currentId);

        $eloList = [
            'iniciante',
            'natureza',
            'estrela',
            'aurora',
            'lotus',
            'kraken',
            'gaia'
        ];
        
        if ($lvl <= 5) {
            $elo = $eloList[0];
        } else if ($lvl <= 10) {
            $elo = $eloList[1];
        } else if ($lvl <= 20) {
            $elo = $eloList[2];
        } else if ($lvl <= 35) {
            $elo = $eloList[3];
        } else if ($lvl <= 55) {
            $elo = $eloList[4];
        } else if ($lvl <= 80) {
            $elo = $eloList[5];
        } else if ($lvl > 80) {
            $elo = $eloList[6];
        }

        $selo = ucwords($elo);

        return [
            'elo' => $elo,
            'selo' => $selo,
        ];
        
    }

    public function getReviews($currentId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $reviewList = [];

        $sql = "SELECT * FROM tb_reviews WHERE cd_to='$currentId' ORDER BY dt_review DESC";

        $query = $mysqli->query($sql);

        $count = $query->num_rows;

        if ($count > 0) {
            foreach($query as $review) {
                array_push($reviewList, [
                    'id' => $review['cd_review'],
                    'to' => $review['cd_to'],
                    'from' => $review['cd_from'],
                    'msg' => $review['ds_review'],
                    'stars' => $review['ds_review_stars'],
                    'date' => $review['dt_review']
                ]);
            }
        }

        return $reviewList;
    }

    public function queryByCnpj($cnpj_id) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $main = new main();
        
        $cnpj_id = str_replace(".", "", $cnpj_id);
        $cnpj_id = str_replace("-", "", $cnpj_id);
        $cnpj_id = str_replace("/", "", $cnpj_id);

        if (strlen($cnpj_id) != 14 || !is_numeric($cnpj_id)) {
            $response = ['error' => true, 'status' => 'Invalid CNPJ', 'code' => 500];
        } else {
            $sql = "SELECT cd_usuario FROM tb_usuarios WHERE cd_cpf_cnpj = '$cnpj_id'";
            $query = $mysqli->query($sql);
            $count = $query->num_rows;
            if ($count > 0) {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $id = $row['cd_usuario'];

                $sql = "SELECT qt_nivel FROM tb_configs WHERE cd_usuario='$id'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_NUM);
                $level = $row[0];

                $sql = "SELECT nm_usuario FROM tb_usuarios WHERE cd_usuario='$id'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_NUM);
                $name = $row[0];

                $sql = "SELECT dt_criacao FROM tb_usuarios WHERE cd_usuario='$id'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_NUM);
                $member_since = $row[0];

                $sql = "SELECT qt_material FROM tb_configs WHERE cd_usuario='$id'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_NUM);
                $material = $row[0];

                $sql = "SELECT ds_atuacao FROM tb_configs WHERE cd_usuario='$id'";
                $query = $mysqli->query($sql);
                $row = $query->fetch_array(MYSQLI_NUM);
                $operations = $row[0];

                $elo = $this->getElo($id);
                $cnpj = $cnpj_id;
                $url = $this->sys_host."/perfil.php?user=".$id;
                $profile_picture = $this->sys_host."/".$this->getImage($id, true);

                // $operations = [
                //     'state1' => 'citie',
                //     'state2' => 'citie',
                //     'state3' => 'citie',
                // ];

                $response = [
                    'error' => false,
                    'status' => 'OK',
                    'code' => 200,
                    'id' => $id,
                    'name' => $name,
                    'url' => $url,
                    'profile_picture' => $profile_picture,
                    'cnpj' => $cnpj,
                    'level' => $level,
                    'elo' => $elo,
                    'member_since' => $member_since,
                    'material' => $material,
                    'operations' => $operations
                ];
            } else {
                $response = ['error' => true, 'status' => 'CNPJ not found', 'code' => 404];
            }
        }
        return json_encode($response, JSON_FORCE_OBJECT);
    }

    public function subirDeNivel($newXp = 0, $currentId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        $lvl = $this->consultar('nivel', $currentId);
        $mat = $this->consultar('material', $currentId);
        $xp = $this->consultar('xp', $currentId);
        
        $need = (1/3) * (pow($lvl, 3) - 6 * pow($lvl, 2) + 17 * $lvl - 12);
        $need = $lvl == 1 ? 1 : $need;
        
        $mat = $mat + $newXp;

        
        if ($xp + $newXp >= $need) {
            //se upar, pega a diferença entre o XP total e a soma do XP atual com o XP adicionado
            //faz update dessa diferença na coluna XP do user e faz um ++ na coluna de nível do usuario
            
            $xp = ($xp + $newXp) - ($need);
            $lvl++;
            $sql = "UPDATE tb_configs SET qt_material = '$mat', ds_xp = '$xp', qt_nivel = '$lvl' WHERE cd_usuario = '$currentId'";
            
        } else {
            //se n upar, soma XP atual com XP adicionado e faz update
            $xp = $xp + $newXp;
            $sql = "UPDATE tb_configs SET qt_material = '$mat', ds_xp = '$xp' WHERE cd_usuario = '$currentId'";
        }
        
        if ($mysqli->query($sql)) {
            return true;
        } else {
            return false;
        }

    }

    public function consultar($info, $currentId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

        switch ($info) {
            case 'xp': $sql = "SELECT ds_xp FROM tb_configs WHERE cd_usuario='$currentId'"; break;
            case 'rua': $sql = "SELECT ds_rua FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'cep': $sql = "SELECT ds_cep FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'tipo': $sql = "SELECT cd_tipo FROM tb_usuarios WHERE cd_usuario='$currentId'"; break;
            case 'tema': $sql = "SELECT cd_theme FROM tb_configs WHERE cd_usuario='$currentId'"; break;
            case 'descartes': $sql = "SELECT * FROM tb_descartes WHERE cd_usuario='$currentId'"; break;
            case 'nivel': $sql = "SELECT qt_nivel FROM tb_configs WHERE cd_usuario='$currentId'"; break;
            case 'nome': $sql = "SELECT nm_usuario FROM tb_usuarios WHERE cd_usuario='$currentId'"; break;
            case 'premium': $sql = "SELECT ic_premium FROM tb_configs WHERE cd_usuario='$currentId'"; break;
            case 'estado': $sql = "SELECT cd_estado FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'cidade': $sql = "SELECT cd_cidade FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'bairro': $sql = "SELECT ds_bairro FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'numero': $sql = "SELECT ds_numero FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
            case 'exists': $sql = "SELECT cd_usuario FROM tb_usuarios WHERE cd_usuario='$currentId'"; break;
            case 'criacao': $sql = "SELECT dt_criacao FROM tb_usuarios WHERE cd_usuario='$currentId'"; break;
            case 'material': $sql = "SELECT qt_material FROM tb_configs WHERE cd_usuario='$currentId'"; break;
            case 'complemento': $sql = "SELECT ds_complemento FROM tb_enderecos WHERE cd_usuario='$currentId'"; break;
        }

        $query = $mysqli->query($sql);

        if ($info == 'exists') {
            $count = $query->num_rows;
            if ($count > 0)
                return true;
            else
                return false;
        }

        if ($info == 'descartes') {
            return $query;
        }

        $row = $query->fetch_array(MYSQLI_NUM);
        return $row[0];
    }

    public function consultarEnderecoFormatado($currentId) {
        include_once 'class.utils.php';
        $utils = new utils();

        $cidade = $this->consultar('cidade', $currentId);
        $estado = $this->consultar('estado', $currentId);
        $bairro = $this->consultar('bairro', $currentId);
        $cep = $this->consultar('cep', $currentId);
        $numero = $this->consultar('numero', $currentId);
        $rua = $this->consultar('rua', $currentId);

        $cidade = $utils->codeToLocale($cidade, 'cidade');
        $estado = $utils->codeToLocale($estado, 'estado');
        $estado = $utils->convertUf($estado, 'reverse');

        return "$rua, $numero - $bairro, $cidade - $estado, $cep";
    }

    public function getAgendas($currentId) {
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        $sql = "SELECT * FROM tb_agendas WHERE cd_usuario='$currentId'";
        return $mysqli->query($sql);
    }

    public function logar($login, $senha) {
        include_once 'class.utils.php';
        $utils = new utils();
        $mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);
        
        $login = $mysqli->real_escape_string($login);
        $login = mb_convert_case($login, MB_CASE_LOWER, "UTF-8");
        $senha = $utils->crypts($senha);
        
        $sql = "SELECT cd_usuario FROM tb_logins WHERE ds_email = '$login' AND cd_senha = '$senha' OR nm_login = '$login' AND cd_senha = '$senha'";
        $query = $mysqli->query($sql);
        $count = $query->num_rows;
        if ($count > 0) {
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $userId = $row['cd_usuario'];
            $sql = "SELECT cd_usuario, ic_desativado FROM tb_usuarios WHERE cd_usuario = '$userId'";
            $query = $mysqli->query($sql);
            $row = $query->fetch_array(MYSQLI_ASSOC);
            $desativado = $row['ic_desativado'];
            if ($desativado == 0) {
                $_SESSION['sessao'] = $row['cd_usuario'];
                return array( "id" => $row['cd_usuario'], "code" => 0 );
            } else
                return array( "code" => 1 );
        } else
            return array( "code" => 2 );
    }

    public function sair() {
        unset($_SESSION['sessao']);
        session_destroy();
        header('location: index.php');
    }
}
?>
