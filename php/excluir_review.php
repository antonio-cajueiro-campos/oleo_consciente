<?php
include_once 'main.php';
include_once 'classes/class.review.php';

if (isset($_POST['review']) && isset($_SESSION['sessao'])) {
	$currentId = $_SESSION['sessao'];
	$reviewId = $_POST['review'];

	$review = new review();

	if ($review->apagar($reviewId, $currentId)) {
		echo "msgShow(71, 1)";
	} else {
		echo "msgShow(1000)";
	}
}

?>