<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-mypurchases.html");
		
		if(!$load)
			die ("Loading template has failed!");
		
		$temp->addBlock("logoutLnk");	
		loadPage($temp);
		$temp->generateOutput();
		
	}
	else{
		header("Location: login.php");
		exit();
	}
		
		
	function loadPage(&$temp){
		
		$userId = $_SESSION[SESSION_USER_KEY]->Id;
		$data = OrderTransaction::LoadByBuyer($userId);
		
		if(is_null($data))
				$temp->addBlock("purchaseNoResults");
			else{
				$temp->addBlock("purchaseListHead");
				
				foreach($data as $listItem){
					$temp->setVariable("p_sellerid", strval($listItem->SellingUserId));
					$temp->setVariable("p_transdate", $listItem->TransactionDate);
					$temp->setVariable("p_saleamt", "$".strval(number_format($listItem->SaleAmount,2)));
					$temp->setVariable("p_listingid", "<a class=\"default-link-btn\" href=\"listingdetail.php?id=".$listItem->SoldListing->Id."\">View Listing</a>");
					
					$temp->addBlock("purchaseItem");
				}
				$temp->addBlock("purchaseListFooter");
			}			
	}
	
?>