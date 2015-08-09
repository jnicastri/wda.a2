<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-newlisting.html");
		
		if(!$load)
			die ("Loading template has failed!");
			
		loadPage($temp);
		$temp->generateOutput();
		
	}
	else{
		header("Location: login.php");
		exit();
	}	
	
	function loadPage(&$temp){
	
		// Bind Categories
		$cats = Category::GetAll();
		
		foreach($cats as $cat){
			$temp->setVariable("c_id", $cat->Id);
			$temp->setVariable("c_name", $cat->Name);
			
			$temp->addBlock("categorySel");
		}
	}
	
?>