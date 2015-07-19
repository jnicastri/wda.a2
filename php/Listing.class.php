<?php
	require_once("includes/classrequires.php");
	
	class Listing extends AbstractBase{
		
		const STATUS_ACTIVE = 0;
		const STATUS_EXPIRED = 1;
		const STATUS_SCHEDULED = 2;
		
		public $ListedDate;
		public $EndDate;
		public $ItemId;
		public $UserId;
		public $ReserveAmount;
		public $BidIncrementAmount;
		public $ShippingAmount;
		public $Bids;
		
		function __construct(){ }
		
		function Status(){
		
			$now = new DateTime();
		
			if($ListedDate > $now){
				return Listing::STATUS_SCHEDULED;
			}
			else if($now >= $ListedDate && $now < $EndDate){
				return Listing::STATUS_ACTIVE;
			}
			else{
				return Listing::STATUS_EXPIRED;
			}	
		}
		
		static function LoadUserById($uid){
			
		} 
		
		function GetTopBid(){
			
			
			
		}
		
		static function GetNew($listDate, $endDate, $itemId, $userId, $resAmt, $shipAmt, $bidIncr){
			
			$newListing = new Listing();
			
			$newListing->ListedDate = $listDate;
			$newListing->EndDate = $endDate;
			$newListing->ItemId = $itemId;
			$newListing->UserId = $userId;
			$newListing->ReserveAmount = $resAmt;
			$newListing->ShippingAmount = $shipAmt;
			$newListing->BidIncrementAmount = $bidIncr;
			
			$newListing->Save();
			return $newListing;
		}
		
		function Save(){
			
			
		}
	}
?>