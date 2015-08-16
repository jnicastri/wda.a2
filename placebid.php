<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$listId = tryParseToNull($_GET["id"], "int");
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-placebid.html");
			
		if(!$load)
			die ("Loading template has failed!");
		
		$temp->addBlock("logoutLnk");
		
		if(is_null($listId)){
			header("Location: index.php");
			exit();
		}
			
		loadPage($listId, $temp);
		$temp->generateOutput();		
	}
	else{
		header("Location: login.php");
		exit();
	}
	
	
	function loadPage($listId, &$temp){
			
		$listing = Listing::LoadById($listId);
		
		if(is_null($listing)){
			header("Location: index.php");
			exit(); 
		}
		$item = Item::LoadById($listing->ItemId);
		
		if(is_null($item)){
			header("Location: index.php");
			exit(); 
		}
		$temp->setVariable("i_title", $item->Name);
		$temp->setVariable("l_bidamt", "$".strval(number_format($listing->BidIncrementAmount,2)));
		$temp->setVariable("l_end", strval($listing->EndDate));
		$temp->setVariable("listingIdHf", strval($listing->Id));
		
		$topBid = $listing->GetTopBid();
		
		!is_null($topBid) 
		? $temp->setVariable("l_top", "$".strval(number_format($topBid->BidValue,2))) 
		: $temp->setVariable("l_top", "No Bids");
	}
	
	
?>