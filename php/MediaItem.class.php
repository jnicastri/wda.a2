<?php
	require_once("includes/classrequires.php");
	
	class MediaItem extends AbstractBase{
		public $ItemId;
		public $FileName;
		public $IsPrimary;
		public $IsActive;
		
		function __construct(){ }
		
		// Static loader to load by owning item
		static function LoadByItemId($itemId){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL MediaItem_GetAllByItemId(?)');
			
			$stmt->bindValue(1, $itemId, PDO::PARAM_INT);
			
			$stmt->execute();
			$mediaItems = Array();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$item = new MediaItem();
				
				$item->Id= intval($record['Id']);
				$item->ItemId = intval($record['ItemId']); 
				$item->FileName = $record['FileName'];
				$record['IsPrimary'] == chr(0x01) ? $item->IsPrimary = true : $item->IsPrimary = false;
				$record['IsActive'] == chr(0x01) ? $item->IsActive = true : $item->IsActive = false;
					
				array_push($mediaItems, $item);
			}
			return $mediaItems;
		}
		
		// Updates the status of the image
		function UpdateStatus($status, $primary){
			
			$this->IsActive = $status;
			$this->IsPrimary = $primary;
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL MediaItem_UpdateStatus(?,?,?)');
			
			$stmt->bindValue(1, $this->$Id, PDO::PARAM_INT);
			$status == true ? $stmt->bindValue(2, 1, PDO::PARAM_INT) : $stmt->bindValue(2, 0, PDO::PARAM_INT); 
			$primary == true ? $stmt->bindValue(3, 1, PDO::PARAM_INT) : $stmt->bindValue(3, 0, PDO::PARAM_INT);
			$stmt->execute();
		}
		
		function Save(){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL MediaItem_Insert(?,?,?,?,@id)');
			
			$stmt->bindValue(1, $this->ItemId, PDO::PARAM_INT);
			$stmt->bindValue(2, $this->FileName, PDO::PARAM_STR);
			$this->IsPrimary == true ? $stmt->bindValue(3, 1, PDO::PARAM_INT) : $stmt->bindValue(3, 0, PDO::PARAM_INT);
			$this->IsActive == true ? $stmt->bindValue(4, 1, PDO::PARAM_INT) : $stmt->bindValue(4, 0, PDO::PARAM_INT);
			
			$stmt->execute();
			$id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
			$this->Id = intval($id[0]["@id"]);
			
		}
		
	}	
	
?>