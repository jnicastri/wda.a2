<?php
	require_once("includes/classrequires.php");
	
	class Listing extends AbstractBase{
		
		const STATUS_ACTIVE = 0;
		const STATUS_EXPIRED = 1;
		const STATUS_SCHEDULED = 2;
		const STATUS_ANY = 3;
		
		public $ListedDate;
		public $EndDate;
		public $ItemId;
		public $UserId;
		public $ReserveAmount;
		public $BidIncrementAmount;
		public $ShippingAmount;
		private $Bids;
		
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
		
		static function LoadByUserId($uid, $status){
			
			$listings = Array();
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL Listing_GetByUser(?, ?)');
			$stmt->bindValue(1, $uid, PDO::PARAM_INT);
			$stmt->bindValue(2, $status, PDO::PARAM_INT);
			
			$stmt->execute();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$item = new Listing();
				
				$item->Id= $record['Id'];
				$item->ItemId = $record['ItemId']; 
				$item->UserId = $record['UserId'];
				$item->ListedDate = $record['ListedDate'];
				$item->EndDate = $record['EndDate'];
				$item->ReserveAmount = $record['ReserveAmount'];
				$item->ShippingAmount = $record['ShippingAmount']; 
				$item->BidIncrementAmount = $record['BidIncrementAmount'];
		
				array_push($listings, $item);
			}
			
			return count($listings) > 0 ? $listings : null;
		} 
		
		static function LoadById($listingId){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL Listing_LoadById(?)');
			$stmt->bindValue(1, $listingId, PDO::PARAM_INT);
			
			$stmt->execute();

			$item = new Listing();
			
			$item->Id= $record['Id'];
			$item->ItemId = $record['ItemId']; 
			$item->UserId = $record['UserId'];
			$item->ListedDate = $record['ListedDate'];
			$item->EndDate = $record['EndDate'];
			$item->ReserveAmount = $record['ReserveAmount'];
			$item->ShippingAmount = $record['ShippingAmount']; 
			$item->BidIncrementAmount = $record['BidIncrementAmount'];
	
			return $item;
		}
		
		function GetTopBid(){
			
			if($this->Bids == null)
				$this->Bids = Bid::LoadByListing($this->Id);
			
			$topBid = null;
			
			if($this->Bids != null){
				foreach($this->Bids as $bid){
					if($topBid == null && $bid->Status == Bid::STATUS_ACTIVE)
						$topBid = $bid;
					else{
						if(($bid->BidValue > $topBid->BidValue) && $bid->Status == Bid::STATUS_ACTIVE)
							$topBid = $bid;			
					}
				}
			}
			return $topBid;
		}
		
		function GetBids(){
			if($this->Bids == null)
				$this->Bids = Bid::LoadByListing($this->Id);
				
			return $this->Bids;
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
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			if($this->IsNew()){
				
				$stmt = $con->prepare('CALL Listing_Insert(?,?,?,?,?,?,? @id)');
				
				$stmt->bindValue(1, $this->ListedDate, PDO::PARAM_DATETIME);
				$stmt->bindValue(2, $this->EndDate, PDO::PARAM_DATETIME);
				$stmt->bindValue(3, $this->ItemId, PDO::PARAM_INT);
				$stmt->bindValue(4, $this->UserId, PDO::PARAM_INT);
				$stmt->bindValue(5, strval($this->ReserveAmount), PDO::PARAM_STR);
				$stmt->bindValue(6, strval($this->ShippingAmount), PDO::PARAM_STR);
				$stmt->bindValue(7, strval($this->BidIncrementAmount), PDO::PARAM_STR);
			
				$stmt->execute();
				
				$this->Id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
			}
			else{
				
				$stmt = $con->prepare('CALL Listing_Update(?,?,?,?,?,?,?,?)');
				
				$stmt->bindValue(1, $this->ListedDate, PDO::PARAM_INT);
				$stmt->bindValue(2, $this->ListedDate, PDO::PARAM_DATETIME);
				$stmt->bindValue(3, $this->EndDate, PDO::PARAM_DATETIME);
				$stmt->bindValue(4, $this->ItemId, PDO::PARAM_INT);
				$stmt->bindValue(5, $this->UserId, PDO::PARAM_INT);
				$stmt->bindValue(6, strval($this->ReserveAmount), PDO::PARAM_STR);
				$stmt->bindValue(7, strval($this->ShippingAmount), PDO::PARAM_STR);
				$stmt->bindValue(8, strval($this->BidIncrementAmount), PDO::PARAM_STR);
			
				$stmt->execute();
			}
			
		}
	}
?>