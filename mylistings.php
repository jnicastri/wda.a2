<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-mylistings.html");
		
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
		$data = Listing::LoadByUserId($userId, Listing::STATUS_ANY);
		
		if(is_null($data))
			$temp->addBlock("listingsNoResults");
		else{
			$temp->addBlock("listingsListHead");
		
		
			foreach($data as $listItem){	
				$topBid = $listItem->GetTopBid();
				$temp->setVariable("l_listdate",$listItem->ListedDate);
				$temp->setVariable("l_enddate", $listItem->EndDate);
				$temp->setVariable("l_listitemid", "<a class=\"default-link-btn\" href=\"listingdetail.php?id=".strval($listItem->Id)."\">View Listing</a>");
				$temp->setVariable("l_resamt", "$".strval(number_format($listItem->ReserveAmount,2)));
				$temp->setVariable("l_shipamt", "$".strval(number_format($listItem->ShippingAmount,2)));
				is_null($topBid) ? 
				$temp->setVariable("l_topbid", "No Bids") :
				$temp->setVariable("l_topbid", "$".strval($topBid->BidValue));
				
				$temp->addBlock("listingItem");
			}
			$temp->addBlock("listingsListFooter");
		}			
	}
	
?>