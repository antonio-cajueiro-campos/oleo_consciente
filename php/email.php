<?php
	// Configuração
	include_once 'main.php';
	include_once 'classes/class.utils.php';
    $utils = new utils();
	$assunto = "Recuperar conta";
	$nome_site = $system->nome_site;
	$titulo = "Sistema $nome_site";
	// $remetente = 'contato@oleoconsciente.com';
	// $senha = 'batatapalha';
	$remetente = 'enviador.24587542@gmail.com';
	$senha = 'senha.24587542';
	$nome = "";
	$code = "";
	$currentId = "0";
	$isEmail = false;
	$recInfo = "";

	if (!isset($_POST['recInfo']) || $_POST['recInfo'] == "" || empty($_POST['recInfo'])) {
		header('location: ../php/msgRedirect.php?to=recuperar_conta&id=1&type=0');
	} else {
		$recInfo = $_POST['recInfo'];
	}


	if (preg_match('/@/', $recInfo)) {
		$sql = "SELECT cd_usuario, ds_email FROM tb_logins WHERE ds_email ='$recInfo'";
		$query = $mysqli->query($sql);
		$count = $query->num_rows;
		$isEmail = true;
	} else {
		$sql = "SELECT cd_usuario FROM tb_usuarios WHERE cd_cpf_cnpj ='$recInfo'";
		$query = $mysqli->query($sql);
		$count = $query->num_rows;
		$isEmail = false;
	}
	
	if ($count > 0) {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$currentId = $row['cd_usuario'];

		if ($isEmail) {
			$destinatario = $row['ds_email'];
		} else {
			$sql = "SELECT ds_email FROM tb_logins WHERE cd_usuario ='$currentId'";
			$query = $mysqli->query($sql);
			$row = $query->fetch_array(MYSQLI_ASSOC);
			$destinatario = $row['ds_email'];
		}

		// Criar o token
		$codeUser = $utils->crypts(md5(uniqid(rand(), true)));

		// pegar o nome do dono do E-Mail ou CPF/CNPJ
		$sql = "SELECT nm_usuario FROM tb_usuarios WHERE cd_usuario ='$currentId'";
		$query = $mysqli->query($sql);
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$nome = $row['nm_usuario'];
		
		
		// Setar o token no banco de dados
		$sql = "UPDATE tb_usuarios SET cd_code = '$codeUser' WHERE cd_usuario = '$currentId'";
		$mysqli->query($sql);
		enviarEmailRec($nome, $codeUser, $currentId, $nome_site, $remetente, $senha, $destinatario, $titulo, $assunto);
	} else {
		header('location: ../php/msgRedirect.php?to=recuperar_conta&id=21&type=0');
	}

//============================== função pra enviar email de recuperação  ===========================

function enviarEmailRec($nome, $code, $currentId, $nome_site, $remetente, $senha, $destinatario, $titulo, $assunto) {
	$gmt = -3;
	$tempo = gmdate("d/m/Y H:i:s", time() + 3600*($gmt+date("I")));

	$corpo_sem_html = "Caro $nome, nosso sistema recebeu uma requisição de recuperação de conta, copie este link para seu navegador para ativar";

	$corpo = "
	<html>
		<head>
			<meta charset=UTF-8>
			<style type='text/css'>
				body {
					padding:0;
					margin:0;
				}

				.wrapper {
					position: relative;
					padding:0px 20px;
					margin:20px;
					background: #73d5d580;
					border-radius: 20px;
					border: black 3px solid;
				}
				
				h2, p {
					color:rgb(0, 0, 0);
				}
				
				p {
					font-size: 20px;
				}

				hr {
					background: rgb(0, 0, 0);
					border: rgb(0, 0, 0) solid 2px;
				}
				
				.logo {
					position: relative;
					border-radius: 10px;
					border: 3px black solid;
					left: 50%;
					transform: translate(-50%);
					margin: 20px 0;
					height: 120px; 
				}
			</style>
		</head>
		<body>
			<div class=wrapper>
				<h2 class=titulo>Olá, recebemos uma requisição para recuperação da sua conta:</h2>
				<div class=msg>
					<p>Sr(a) $nome, sua conta foi requisitada para uma recuperação de senha, para alterar sua senha clique no link abaixo:</p>
					<a href='https://antonio.servegame.com/oil_rescue/alterar_senha.php?token=$code&userAuth=$currentId'><p>Link para recuperação</p></a>
					(você tem até meia noite antes da URL expirar)
				</div>
				<hr>
				<div class=contatos>
					<h2 class=titulo>Caso não tenha sido você que requisitou ou simplesmente requisitou por acidente, desconsidere esse e-mail.</h2>
					<p>Fique atento em relação a sua senha, nossos administradores nunca irão pedir por ela.</p>
					<p>Atenciosamente, $nome_site</p>
				</div>
				<hr>
				<div class=info>
					<p>Data e hora de requisição: $tempo</p>
					<p>Este é um email automático, favor não responder.</p>
				</div>
			</div>
		</body>
	</html>";

	require_once("classes/class.phpmailer.php");
	require_once("classes/class.smtp.php");

	define('GUSER', $remetente);
	define('GPWD', $senha);

	function mailto($para, $de, $de_nome, $assunto, $corpo, $corpo_sem_html) { 
		global $error;
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'smtp.gmail.com';
		//$mail->Host = 'localhost';
		//$mail->Port = 587;
		$mail->Port = 465;
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->CharSet = 'UTF-8';
		$mail->SetFrom($de, $de_nome);
		$mail->addCustomHeader('X-custom-header: custom-value');
		$mail->AddAddress($para);
		$mail->isHTML(true);
		$mail->Subject = $assunto;
		$mail->Body = $corpo;
		$mail->AltBody = $corpo;

		if(!$mail->Send()) {
			echo $error = 'Mail error: '.$mail->ErrorInfo; 
			return false;
		} else {
			echo $error = 'Mensagem enviada!';
			return true;
		}
	}

	if (mailto($destinatario, $remetente, $titulo, $assunto, $corpo, $corpo_sem_html)) {
		header('location: ../php/msgRedirect.php?to=recuperar_conta&id=22&type=1');
	} else {
		header('location: ../php/msgRedirect.php?to=recuperar_conta&id=58&type=0');
	}
}

?>