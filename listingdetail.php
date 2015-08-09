<?php
	require_once("includes/requirebundle.php");
	
	$listId = tryParseToNull($_GET["id"], "int");
	
	$temp = new MiniTemplator;
	$load = $temp->readTemplateFromFile("html/tmpl-listingdetail.html");
		
	if(!$load)
		die ("Loading template has failed!");
	
	if(is_null($listId))
		header("Location: index.php"); 	
	
	loadPage($listId, $temp);
	
	$temp->generateOutput();
	
	function loadPage($listId, &$temp){
		
		$listing = Listing::LoadById($listId);		

		if(is_null($listing))
			header("Location: index.php"); 
			
		$item = Item::LoadById($listing->ItemId);
		
		if(is_null($item))
			header("Location: index.php");
		
		// Binding text data
		$temp->setVariable("i_title", $item->Name);
		$temp->setVariable("i_cat", $item->Category->Name);
		$temp->setVariable("l_shipamt", "$".strval(number_format($listing->ShippingAmount,2)));
		$temp->setVariable("l_bidamt", "$".strval(number_format($listing->BidIncrementAmount,2)));
		$temp->setVariable("l_start", strval($listing->ListedDate));
		$temp->setVariable("l_end", strval($listing->EndDate));
		
		$topBid = $listing->GetTopBid();
		
		!is_null($topBid) 
		? $temp->setVariable("l_top", "$".strval(number_format($topBid->BidValue,2))) 
		: $temp->setVariable("l_top", "No Bids");
		
		//Binding Images
		$primaryImage = $item->GetPrimaryMediaItem();

		if(!is_null($primaryImage)){
			$temp->setVariable("primaryimg", MEDIA_ITEM_PATH . $primaryImage->FileName);
			$temp->addBlock("PrimaryImage");
		}
		
		//Description
		$temp->setVariable("i_desc", $item->LongDescription);
		
		//TODO: Handle Multiple Images
		
		// Place a Bid link
		if($listing->Status() == Listing::STATUS_ACTIVE){
			$temp->setVariable("itemBidId", $item->Id);
			$temp->addBlock("BidBtn");
		}
		
	}
?>
