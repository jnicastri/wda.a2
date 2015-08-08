<?php
	require_once("includes/classrequires.php");
	
	class Bid extends AbstractBase{
	
		const STATUS_ACTIVE = 0;
		const STATUS_DELETED = 1;
		
		public $ListingId;
		public $BiddingUserId;
		public $BidValue;
		public $Status;
		
		function __construct(){ }
		
		static function GetNew($listingId, $userId, $bidValue){
			
			$newBid = new Bid();
			
			$newBid->ListingId = $listingId;
			$newBid->BiddingUserId = $userId;
			$newBid->BidValue = $bidValue;
			$newBid->Status = Bid::STATUS_ACTIVE;
			
			$newBid->Save();
			return $newBid;
		}
		
		function Save(){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL Bid_Insert(?,?,?,?,@id)');
				
			$stmt->bindValue(1, $this->ListingId, PDO::PARAM_INT);
			$stmt->bindValue(2, $this->BiddingUserId, PDO::PARAM_INT);
			$stmt->bindValue(3, strval(number_format($this->BidValue, 2)), PDO::PARAM_STR);
			$stmt->bindValue(4, $this->Status, PDO::PARAM_INT);
			$stmt->execute();

			$this->Id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
		}
		
		static function Delete($id){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL Bid_Delete(?)');
			$stmt->bindValue(1, $id, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		static function LoadByListing($listingId){
			
			$bids = Array();
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL Bid_LoadBidsByListing(?)');
			$stmt->bindValue(1, $listingId, PDO::PARAM_INT);
			
			$stmt->execute();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$item = new Bid();
				
				$item->Id= $record['Id'];
				$item->ListingId = intval($record['ListingId']); 
				$item->BiddingUserId = intval($record['BiddingUserId']);
				$item->BidValue = tryParseToNull($record['BidValue'], "float");
				$item->Status = intval($record['Status']);
		
				array_push($bids, $item);
			}
			
			return count($bids) > 0 ? $bids : null;
		}
	}
?>