<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/tmpl-mybidhistory.html");
		
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
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		$con = new PDO($conStr, DB_USER, DB_PWD);
		
		$stmt = $con->prepare('CALL Bid_LoadListingsBidsByUser(?)');
		$stmt->bindValue(1, intval($_SESSION[SESSION_USER_KEY]->Id), PDO::PARAM_INT);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
		
			$temp->addBlock("tableHeader");
		
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
					
				$bidId = $record['BidId'];
				$temp->setVariable("l_id", "<a class=\"default-link-btn\" href=\"listingdetail.php?id=".strval($record['ListingId'])."\">View Listing</a>");
				$temp->setVariable("l_end", strval($record['ListingEndDate'])); 
				$temp->setVariable("i_name", strval($record['ItemName']));
				$temp->setVariable("b_amt", strval($record['BidValue']));
				$stat = intval($record['BidStatus']);
		
				if($stat == Bid::STATUS_ACTIVE){
					$temp->setVariable("b_status", "<a class=\"default-link-btn red-variant\" href=\"p_delBid.php?id=".$bidId."\">Delete Bid</a>");
				}
				else{
					$temp->setVariable("b_status", "Deleted");
				} 
		
				$temp->addBlock("tableRow");
			}
			
			$temp->addBlock("tableFooter");
		}
		else{
			$temp->addBlock("noResults");
		}
	}
	
?>