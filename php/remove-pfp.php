<?php
include_once 'main.php';

if (isset($_POST['r']) && isset($_SESSION['sessao'])) {
	$currentId = $_SESSION['sessao'];

	$userProfilePath = "../images/user_photos/$currentId/";

	if (is_dir($userProfilePath)) {
		if ($dh = opendir($userProfilePath)) {
			while (($file = readdir($dh)) !== false) {
				if ($file != "." && $file != "..")
				unlink($userProfilePath.$file);
			}
			closedir($dh);
			echo "msgShow(67, 1)";
		}
	}
}
?>
