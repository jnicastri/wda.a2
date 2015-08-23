<?php
	require_once("includes/requirebundle.php");
	
	function loadPage(){
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-login.html");
		
		if(!$load)
			die ("Loading template has failed!");
		
		
		if(isset($_SESSION[SESSION_LOGIN_ERROR])){
			$temp->setVariable("helperMsg", $_SESSION[SESSION_LOGIN_ERROR]);
			$temp->addBlock("prevError");
		}
			
		$temp->generateOutput();
	}
	
	loadPage();
	
?>