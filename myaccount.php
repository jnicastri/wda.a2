<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-myaccount.html");
		
		if(!$load)
			die ("Loading template has failed!");
		
		if(isset($_GET['error']))
			$temp->addBlock("UpdateError");
		else if(isset($_GET['success']))
			$temp->addBlock("SuccessPh");
		
		$temp->addBlock("logoutLnk");	
		loadPage($temp);
		$temp->generateOutput();
	}	
	else{
		header("Location: login.php");
		exit();
	}	
	
	function loadPage(&$temp){
		
		$user = $_SESSION[SESSION_USER_KEY];
		
		$temp->setVariable("u_fname", $user->FirstName);
		$temp->setVariable("u_lname", $user->LastName);
		$temp->setVariable("u_email", $user->Email);
		$temp->setVariable("u_nname", $user->UserName);
		$temp->setVariable("u_add1", $user->BillingAddress->Line1);
		$temp->setVariable("u_add2", $user->BillingAddress->Line2);
		$temp->setVariable("u_addsub", $user->BillingAddress->Suburb);
		$temp->setVariable("u_addstate", $user->BillingAddress->State);
		$temp->setVariable("u_addzip", $user->BillingAddress->Zip);
		
	}
	
?>