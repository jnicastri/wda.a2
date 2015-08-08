<?php
	
	require_once("includes/requirebundle.php");
	
	$userBid = trim($_POST['bidAmt']);
	$listingId = trim($_POST['listingId']);
	
	if(!empty($userBid) && !empty($listingId)){
	
		$listId = tryParseToNull($listingId, "int");
		$bid = tryParseToNull($userBid, "float");
		
		if(is_null($listId) || is_null($bid)){
			header("Location: index.php");
			exit();		
		}
		else{
			$listing = Listing::LoadById($listId);	
			
			if(is_null($listing)){
				header("Location: index.php"); 
				exit();	
			}
			
			$topBid = $listing->GetTopBid();
			
			if($topBid != null){	
				var_dump($topBid);
				var_dump($bid);
				if($topBid->BidValue >= $bid){
					header("Location: placebid.php?id=".$listing->Id."&error=1");
					exit();	
				}
				
				$diffAmt = $bid - $topBid->BidValue;
				
				if($diffAmt < $listing->BidIncrementAmount){
					header("Location: placebid.php?id=".$listing->Id."&error=1");
					exit();	
				}
			}
			else{
				if($bid < $listing->BidIncrementAmount){
					header("Location: placebid.php?id=".$listing->Id."&error=1");
					exit();	
				}
			}
			
			$newBid = Bid::GetNew($listing->Id, $_SESSION[SESSION_USER_KEY]->Id, $bid);
			
			header("Location: listingdetail.php?id=".strval($listing->Id));	
			exit();				
		}
	}
	else{
		header("Location: index.php");
		exit();	
	}
	
	
?>