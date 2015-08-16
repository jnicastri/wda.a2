<?php
	require_once("includes/requirebundle.php");
	
	if(is_null($_SESSION[SESSION_USER_KEY])){
		header("Location: index.php");
		exit();
	}
	else{
		
		$itemName = trim($_POST['itemNameTb']);
		$itemDesc = trim($_POST['itemDescTa']);
		$itemCatId = trim($_POST['catSelect']);
		$startDt = trim($_POST['startTb']);
		$endDt = trim($_POST['endTb']);
		$resAmt = trim($_POST['resAmtTb']);
		$minBid = trim($_POST['minBidAmtTb']);
		$shipAmt = trim($_POST['shipAmtTb']);
		
		if(isNullOrEmpty($itemName) ||
			isNullOrEmpty($itemDesc) ||
			isNullOrEmpty($itemCatId) ||
			isNullOrEmpty($startDt) ||
			isNullOrEmpty($endDt) ||
			isNullOrEmpty($resAmt) ||
			isNullOrEmpty($minBid) ||
			isNullOrEmpty($shipAmt)){
				header("Location: newlisting.php?error=1");
				exit();
			}
			
		$item = Item::GetNew($itemName, $itemDesc, intval($itemCatId));
		if(is_null($item)){
			header("Location: newlisting.php?error=1");
			exit();
		}
		
		// Handling File Upload (if one is provided)
		if(isset($_FILES['imgUpload'])){

			$fn = pathinfo($_FILES['imgUpload']['name']);
			$ext = $fn['extension'];
			
			$newFn = uniqid().".".$ext;
			$fullPath = __DIR__ ."/". MEDIA_ITEM_PATH . $newFn;
			move_uploaded_file($_FILES['imgUpload']['tmp_name'], $fullPath);
			
			$mediaItem = new MediaItem();
			$mediaItem->ItemId = $item->Id;
			$mediaItem->FileName = $newFn;
			$mediaItem->IsPrimary = true;
			$mediaItem->IsActive = true;
			
			$mediaItem->Save();
		}
		
		$sDt = new DateTime($startDt);
		$eDt = new DateTime($endDt);
		
		$listing = Listing::GetNew($sDt, $eDt, $item->Id,
		$_SESSION[SESSION_USER_KEY]->Id, $resAmt, $shipAmt, $minBid);
			
		if(is_null($listing)){
			header("Location: newlisting.php?error=1");
			exit();
		}
		else{
			header("Location: listingdetail.php?id=".$listing->Id);
			exit();
		}
	}
	
?>