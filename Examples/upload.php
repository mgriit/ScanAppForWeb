<?php
error_reporting(-1);
switch (@$_POST['type'])
{
    	case 'image_upload':
		try {
			if (!isset($_FILES['blob']['error']) || is_array($_FILES['blob']['error'])){
				throw new RuntimeException('Error on uploading image.');
			}
			if ($_FILES['blob']['size'] > 19000000){
				throw new RuntimeException('Exceeded filesize limit.');
			}
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $ext = array_search($finfo->file($_FILES['blob']['tmp_name']), array('jpg' => 'image/jpeg', 'png' => 'image/png'), true)){
				throw new RuntimeException('Bad image type.');
			}
			if ($_FILES['blob']['error'] == UPLOAD_ERR_OK)
			{
				$_dir = '';
				$_temp_filename = bin2hex(random_bytes(5)).'.jpg';
				if (!move_uploaded_file($_FILES['blob']['tmp_name'], $_dir.$_temp_filename)){
					throw new RuntimeException('Error on saving image.');
				}
				// update your project's database
				// ...
				// $file_id = $dbh->lastInsertId();
				$file_id = rand();
				echo json_encode( ['newname' => mb_strimwidth($_temp_filename, 0, 18, '...'), 'file_id' => $file_id] );
			}
			exit;
		} catch (RuntimeException $e) {
			die($e->getMessage());
		}
		break;
}