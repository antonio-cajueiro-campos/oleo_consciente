<?php
include_once 'main.php';
include_once 'vendor/autoload.php';
$response = array();

//header('Content-Type: application/json; charset=utf-8');

if (isset($_FILES['file']) && isset($_SESSION['sessao'])) {
	$currentId = $_SESSION['sessao'];
	$valid = [
		'jpg' => 'image/jpeg',
		'png' => 'image/png',
		'gif' => 'image/gif'
	];

	try {
		if (
		  !isset($_FILES['file']['error']) ||
		  is_array($_FILES['file']['error'])
		) {
		  throw new RuntimeException('Invalid parameters.');
		}
		
		switch ($_FILES['file']['error']) {
		  case UPLOAD_ERR_OK:
			break;
		  case UPLOAD_ERR_NO_FILE:
			throw new RuntimeException('No file sent.');
		  case UPLOAD_ERR_INI_SIZE:
		  case UPLOAD_ERR_FORM_SIZE:
			throw new RuntimeException('Exceeded filesize limit.');
		  default:
			throw new RuntimeException('Unknown errors.');
		}
	  
		if ($_FILES['file']['size'] > 5000000) {
		  throw new RuntimeException('Exceeded filesize limit.');
		}
	  
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		if (false === $ext = array_search($finfo->file($_FILES['file']['tmp_name']), $valid, true)) {
		  throw new RuntimeException('Invalid file format.');
		}

		$userProfilePath = "../images/user_photos/$currentId/";

		if (!file_exists($userProfilePath)) {
			mkdir($userProfilePath, 0777);
		}

        if (is_dir($userProfilePath)) {
            if ($dh = opendir($userProfilePath)) {
                while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != "..")
                    unlink($userProfilePath.$file);
                }
                closedir($dh);
            }
        }
		
		if (!move_uploaded_file($_FILES['file']['tmp_name'], sprintf($userProfilePath."%s.%s", "profile", $ext))) {
			throw new RuntimeException('Failed to move uploaded file.');
		}

		$pro = true;

		if (!$pro) {
			\Gregwar\Image\Image::open($userProfilePath."/profile.$ext")->save($userProfilePath.'profile.jpg', 'jpg', 100);
			if ($ext != "jpg") {
				unlink($userProfilePath."/profile.$ext");
			}
		}


		$response = array(
		  "status" => "success",
		  "error" => false,
		  "message" => "File uploaded successfully"
		);
		echo json_encode($response);
	  
	} catch (RuntimeException $e) {
		$response = array(
		  "status" => "error",
		  "error" => true,
		  "message" => $e->getMessage()
		);
		echo json_encode($response);
	}	  
}
?>
