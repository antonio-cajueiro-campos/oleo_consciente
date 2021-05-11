<?php 
	include '../../php/main.php';
	header('Content-type: application/json');
	
	if (isset($_GET['v2']) && $_GET['v2'] != "") {
		echo $usuario->queryByCnpj($_GET['v2']);
	} else {
		$response = ['error' => true, 'status' => 'Invalid input', 'code' => 500];
		echo json_encode($response, JSON_FORCE_OBJECT);
	}
