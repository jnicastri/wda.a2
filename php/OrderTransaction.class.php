<?php
	require_once("includes/classrequires.php");
	
	class OrderTransaction extends AbstractBase{
		
		public $SellingUserId;
		public $PurchasingUserId;
		public $TransactionDate;
		public $SaleAmount;
		public $ShippingAddress;
		public $SoldListing;
		public $ShippingFirstName;
		public $ShippingLastName;
		
		function __construct(){ }
		
		static function GetNew($listing, $purchaserId, $address, $ccNo, $ccExp, $shipFirstName, $shipLastName){
			
			$newOrder = new OrderTransaction();
			
			$winningBid = $listing->GetTopBid();
			
			$newOrder->SoldListing = $listing;
			$newOrder->SellingUserId = $newOrder->SoldListing->UserId;
			$newOrder->SaleAmount = $winningBid->BidValue + $SoldListing->ShippingAmount;
			$newOrder->ShippingAddress = $address;
			$newOrder->PurchasingUserId = $purchaserId;
			
			$newOrder->Save($ccNo, $ccExp, $shipFirstName, $shipLastName);
			
			return $newOrder;
		}
		
		function Save($ccNo, $ccExp, $shipFirstName, $shipLastName){
			
			if($this->IsNew()){
				$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
				$con = new PDO($conStr, DB_USER, DB_PWD);
				
				$stmt = $con->prepare('CALL OrderTrans_Insert(?,?,?,?,?,?,?,?,?,?,?,?,?, @id, @dt)');
				
				$stmt->bindValue(1, $this->SellingUserId, PDO::PARAM_INT);
				$stmt->bindValue(2, $this->PurchasingUserId, PDO::PARAM_INT);
				$stmt->bindValue(3, strval($this->SaleAmount), PDO::PARAM_STR);
				$stmt->bindValue(4, $this->SoldListing->Id, PDO::PARAM_INT);
				$stmt->bindValue(5, $ccNo, PDO::PARAM_STR);
				$stmt->bindValue(6, $ccExp, PDO::PARAM_STR);
				$stmt->bindValue(7, $this->ShippingAddress->Line1, PDO::PARAM_STR);
				$stmt->bindValue(8, $this->ShippingAddress->Line2, PDO::PARAM_STR);
				$stmt->bindValue(9, $this->ShippingAddress->Suburb, PDO::PARAM_STR);
				$stmt->bindValue(10, $this->ShippingAddress->State, PDO::PARAM_STR);
				$stmt->bindValue(11, $this->ShippingAddress->Zip, PDO::PARAM_STR);
				$stmt->bindValue(12, $shipFirstName, PDO::PARAM_STR);
				$stmt->bindValue(13, $shipLastName, PDO::PARAM_STR);
				
				$stmt->execute();
				
				$id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
				$this->Id = intval($id[0]["@id"]);
				$td = $con->query("SELECT @dt")->fetchAll(PDO::FETCH_ASSOC);
				$this->TransactionDate = new DateTime($td[0]["@dt"]);
			}
		}
		
		static function LoadByBuyer($buyerId){
			
			$transactions = Array();
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL OrderTrans_LoadCollection(?, ?)');
			$stmt->bindValue(1, $buyerId, PDO::PARAM_INT);
			$stmt->bindValue(2, "buyer", PDO::PARAM_STR);
	
			$stmt->execute();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
				
				$item = new OrderTransaction();				
				
				$item->Id= intval($record['Id']);
				$item->SellingUserId = intval($record['SellingUserDetailId']); 
				$item->PurchasingUserId = intval($record['PurchasingUserId']);
				$item->TransactionDate = $record['TransactionDate'];
				$item->SaleAmount = floatval($record['SaleAmount']);
				$item->ShippingFirstName = $record['ShippingFirstName'];
				$item->ShippingLastName = $record['ShippingLastName']; 
				
				$shipAddress = new AddressStruct();
				$shipAddress->Line1 = $record['ShippingAddressLine1'];
				$shipAddress->Line2 = $record['ShippingAddressLine2'];
				$shipAddress->Suburb = $record['ShippingAddressSuburb'];
				$shipAddress->State = $record['ShippingAddressState'];
				$shipAddress->Zip = $record['ShippingAddressZip']; 
				$item->ShippingAddress = $shipAddress;

				$item->SoldListing = Listing::LoadById(intval($record['ListingId']));
				array_push($transactions, $item);
				
				
			}
			
			return count($transactions) > 0 ? $transactions : null;
		} 
		
		static function LoadBySeller($sellerId){
			
			$transactions = Array();
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			$stmt = $con->prepare('CALL OrderTrans_LoadCollection(?, ?)');
			$stmt->bindValue(1, $sellerId, PDO::PARAM_INT);
			$stmt->bindValue(2, "seller", PDO::PARAM_STR);
			$stmt->execute();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$item = new OrderTransaction();
				
				$item->Id= intval($record['Id']);
				$item->SellingUserId = intval($record['SellingUserDetailId']); 
				$item->PurchasingUserId = intval($record['PurchasingUserId']);
				$item->TransactionDate = $record['TransactionDate'];
				$item->SaleAmount = floatval($record['SaleAmount']);
				$item->ShippingFirstName = $record['ShippingFirstName'];
				$item->ShippingLastName = $record['ShippingLastName']; 
				
				$shipAddress = new AddressStruct();
				$shipAddress->Line1 = $record['ShippingAddressLine1'];
				$shipAddress->Line2 = $record['ShippingAddressLine2'];
				$shipAddress->Suburb = $record['ShippingAddressSuburb'];
				$shipAddress->State = $record['ShippingAddressState'];
				$shipAddress->Zip = $record['ShippingAddressZip']; 
				$item->ShippingAddress = $shipAddress;
				
				$item->SoldListing = Listing::LoadById($record['ListingId']);
		
				array_push($transactions, $item);
			}
			
			return count($transactions) > 0 ? $transactions : null;
			
		}
	}
?>