<?php
include_once 'main.php';
include_once 'classes/class.review.php';
include_once 'classes/class.notify.php';

if (isset($_POST['to']) && isset($_SESSION['sessao'])) {
	$currentId = $_SESSION['sessao'];
	$to = $_POST['to'];
	$from = $_POST['from'];
	$notifyId = $_POST['notify'];
	$reviewMsg = $_POST['reviewMsg'];
	$reviewStars = $_POST['reviewStars'];

	$review = new review();
	$notify = new notify();

	if ($currentId == $from) {
		if ($notify->pertence($notifyId, $from, $to)) {
			if ($review->criar($to, $from, $reviewMsg, $reviewStars)) {
				$queryNotify = $notify->consultarNotify($notifyId);
				$descarteId = $queryNotify['cd_descarte'];
				if ($notify->excluir($notifyId, $descarteId)) {
					echo "msgShow(72, 1)";
				} else {
					echo "msgShow(1000)";
				}
			} else {
				echo "msgShow(1001)";
			}
		} else {
			echo "msgShow(1002)";
		}
	} else {
		echo "msgShow(1003)";
	}

}

?>