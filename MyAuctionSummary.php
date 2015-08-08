<?php
	require_once("includes/requirebundle.php");
	
	$temp = new MiniTemplator;
	$load = $temp->readTemplateFromFile("html/tmpl-myauctionsummary.html");
		
	if(!$load)
		die ("Loading template has failed!");
	
	if(!isset($_SESSION[SESSION_USER_KEY]))
		header("Location: login.php");
	else{
		loadPage($temp);
		$temp->generateOutput();
	}
	
	function loadPage(&$temp){
		
		$userId = $_SESSION[SESSION_USER_KEY]->Id;
		
		$myListings = Listing::LoadByUserId($userId, Listing::STATUS_ANY);
		$myPurchases = OrderTransaction::LoadByBuyer($userId);
		$mySales = OrderTransaction::LoadBySeller($userId);
		
		bindData($myListings, "listings", $temp);
		bindData($myPurchases, "purchases", $temp);
		bindData($mySales, "sales", $temp);
		
			
	}
	
	function bindData($data, $collType, &$temp){
		
		if($collType == "listings"){
			if(is_null($collType))
				$temp->addBlock("listingsNoResults");
			else{
				$temp->addBlock("listingsListHead");
				
				foreach($data as $listItem){	
					$topBid = $listItem->GetTopBid();
					$temp->setVariable("l_listdate",$listItem->ListedDate);
					$temp->setVariable("l_enddate", $listItem->EndDate);
					$temp->setVariable("l_listitemid", "<a href=\"listingdetail.php?id=".strval($listItem->ItemId)."\">View Listing</a>");
					$temp->setVariable("l_resamt", "$".strval(number_format($listItem->ReserveAmount,2)));
					$temp->setVariable("l_shipamt", "$".strval(number_format($listItem->ShippingAmount,2)));
					is_null($topBid) ? 
					$temp->setVariable("l_topbid", "No Bids") :
					$temp->setVariable("l_topbid", strval($topBid->BidValue));
					
					$temp->addBlock("listingItem");
				}
				$temp->addBlock("listingsListFooter");
			}			
		}
		else if($collType == "purchases"){
			if(is_null($collType))
				$temp->addBlock("purchaseNoResults");
			else{
				$temp->addBlock("purchaseListHead");
				
				foreach($data as $listItem){
					$temp->setVariable("p_sellerid", strval($listItem->SellingUserId));
					$temp->setVariable("p_transdate", $listItem->TransactionDate);
					$temp->setVariable("p_saleamt", "$".strval(number_format($listItem->SaleAmount,2)));
					$temp->setVariable("p_listingid", "<a href=\"listingdetail.php?id=".$listItem->SoldListing->Id."\">View Listing</a>");
					
					$temp->addBlock("purchaseItem");
				}
				$temp->addBlock("purchaseListFooter");
			}	
		}
		else if($collType == "sales"){
			if(is_null($collType))
				$temp->addBlock("salesNoResults");
			else{
				$temp->addBlock("salesListHead");
				
				foreach($data as $listItem){
					$temp->setVariable("s_buyerid", strval($listItem->PurchasingUserId));
					$temp->setVariable("s_transdate", $listItem->TransactionDate);
					$temp->setVariable("s_saleamt", "$".strval(number_format($listItem->SaleAmount,2)));
					$temp->setVariable("s_listingid", "<a href=\"listingdetail.php?id=".strval($listItem->SoldListing->Id)."\">View Listing</a>");
					$temp->setVariable("s_name", $listItem->ShippingFirstName." ".$listItem->ShippingLastName);
					$temp->addBlock("salesItem");
				}
				$temp->addBlock("salesListFooter");
			}	
		}
	}
	
	
	
			
?>