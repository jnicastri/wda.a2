<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$bidId = tryParseToNull($_GET["id"], "int");

		if(!is_null($bidId)){
			Bid::Delete($bidId);
		}
		
		header("Location: mybidhistory.php");
		exit();
	}
	else{
		header("Location: index.php");
		exit();
	}
	
?>