<?php
class review {
	
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
	
	public function apagar($reviewId, $currentId) {
		$mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

		$sql = "DELETE FROM tb_reviews WHERE cd_review = '$reviewId' AND cd_from = '$currentId'";


		if ($mysqli->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	public function criar($to, $from, $reviewMsg, $reviewStars) {
		include_once 'class.utils.php';
        $utils = new utils();
		$mysqli = new mysqli($this->ht, $this->lg, $this->pw, $this->db);

		$to = $mysqli->real_escape_string($to);
		$from = $mysqli->real_escape_string($from);
		$reviewMsg = $mysqli->real_escape_string($reviewMsg);
		$reviewStars = $mysqli->real_escape_string($reviewStars);

		$new_id = $utils->newId('tb_reviews', 'cd_review');
		$timestamp = date('Y-m-d H:i:s', time());

		$reviewStars = $reviewStars == "" ? 0 : $reviewStars;
		
		$sql = "INSERT INTO tb_reviews (cd_review, cd_to, cd_from, ds_review, dt_review, ds_review_stars) VALUES ('$new_id', '$to', '$from', '$reviewMsg', '$timestamp', '$reviewStars')";

		if ($mysqli->query($sql)) {
			return true;
		} else {
			return false;
		}
	}
}

?>