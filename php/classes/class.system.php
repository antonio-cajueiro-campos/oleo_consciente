<?php
class system {
    private $CREATORS = "Antonio, Tiago, Caio, Felipe";
    private $GVERIFY = "";
    
    private $ht = "";
    private $lg = "";
    private $pw = "";
    private $db = "";

    public $nome_site = "";
    public $site_address = "";
    public $site_email = "";

    public $page_keywords = "";
    public $page_desc = "";
    public $page_abstract = "";
    
    public function setSysConfig($nm, $ht, $email) {
        $this->nome_site = $nm;
        $this->site_address = $ht;
        $this->site_email = $email;
    }

    public function setDbConfig($ht, $lg, $pw, $db) {
        $this->ht = $ht;
        $this->lg = $lg;
        $this->pw = $pw;
        $this->db = $db;
    }

    public function setPageConfig($keyword, $desc, $abstract) {
        $this->page_keywords = $keyword;
        $this->page_desc = $desc;
        $this->page_abstract = $abstract;
    }

    public function getConnection($type){
        switch ($type) {
            case 'ht': return $this->ht;
            case 'lg': return $this->lg;
            case 'pw': return $this->pw;
            case 'db': return $this->db;
        }
    }

    public function getCreators(){
        return $this->CREATORS;
    }

    public function getGverify(){
        return $this->GVERIFY;
    }
}
?>
