<?php
	require_once("includes/requirebundle.php");
	
	unset($_SESSION[SESSION_USER_KEY]);
	
	header("Location: index.php");
		
?>