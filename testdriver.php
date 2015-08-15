<?php
	require_once("includes/requirebundle.php");
	
	if(isset($_POST["submit"])) {
	
		$fn = pathinfo($_FILES['imgUpload']['name']);
		$ext = $fn['extension'];
		
		var_dump($_FILES['imgUpload']);
		
		$newFn = uniqid().".".$ext;
		$fullPath = __DIR__ ."/". MEDIA_ITEM_PATH . $newFn;
		var_dump($fullPath);
		var_dump(move_uploaded_file($_FILES['imgUpload']['tmp_name'], $fullPath));
		
		$mediaItem = new MediaItem();
		$mediaItem->ItemId = 34;
		$mediaItem->FileName = $newFn;
		$mediaItem->IsPrimary = true;
		$mediaItem->IsActive = true;
		
		//var_dump($mediaItem);
		
		$mediaItem->Save();
	
	}
	
	
	
	

	
?>
<!DOCTYPE html>
<html>
	<head>
		
	</head>
	<body>
		<form method="POST" action="testdriver.php" enctype="multipart/form-data">
			Primary Image Upload:
			<input type="file" name="imgUpload" id="imgUpload" />
			<label for="imgUpload"></label>
			<input type="submit" name="submit" value="go" />
		</form>
	</body>
</html>
