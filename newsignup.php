<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		header("Location: myaccount.php");
		exit();	
	}
	
	$temp = new MiniTemplator;
	$load = $temp->readTemplateFromFile("html/tmpl-newsignup.html");	
	
	if(!$load)
		die ("Loading template has failed!");
			
	if(isset($_GET['error']))
			$temp->addBlock("UpdateError");
			
	$temp->addBlock("loginLnk");
	$temp->generateOutput();
	
?>