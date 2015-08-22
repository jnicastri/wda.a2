<?php
	require_once("includes/classrequires.php");
	
	class Item extends AbstractBase{
		public $DateCreated;
		public $Name;
		public $LongDescription;
		public $Category;
		private $mediaItems;
		
		function __construct(){ }
		
		//Static Loaders
		static function LoadById($id){
			$loadItem = new Item();
			$loadItem->Load($id);
			
			return $loadItem;
		}
		
		// Commits a new Item to the DB
		static function GetNew($name, $desc, $catId){
			$newItem = new Item();
			
			$newItem->Name = $name;
			$newItem->LongDescription = $desc;
			$newItem->Category = Category::LoadById(intval($catId));
			
			$newItem->Save();
			
			return $newItem;
		}
		
		//Instance Functions
		function Load($id){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Item_Load(?)');
			
			$stmt->bindValue(1, intval($id, PDO::PARAM_INT));
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$this->Id = intval($record['Id']);
			$this->Name = $record['Name'];
			$this->LongDescription = $record['LongDescription'];
			$this->DateCreated = $record['DateCreated'];
			
			$this->Category = new Category();
			
			if(intval($record['CatId']) != 0){
				
				$this->Category->Id = intval($record['CatId']);
				$this->Category->Name = $record['CatName'];
				$this->Category->Description = $record['CatDesc'];
			}
			else{
				$this->Category->Name = "Not Set";
			}
		}
	
		function Save(){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			if($this->IsNew()){

				$stmt = $con->prepare('CALL Item_Insert(?,?,?, @id, @createdDate)');
				
				$stmt->bindValue(1, $this->Name, PDO::PARAM_STR);
				$stmt->bindValue(2, $this->LongDescription, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->Category->Id, PDO::PARAM_INT);
			
				$stmt->execute();
				
				//print_r($stmt->errorInfo());
				
				$id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
				$this->Id = intval($id[0]["@id"]);
				$dc = $con->query("SELECT @createdDate")->fetchAll(PDO::FETCH_ASSOC);
				$this->DateCreated = new DateTime($dc[0]["@createdDate"]);
			}
			else{
				
				$stmt = $con->prepare('CALL Item_Update(?,?,?,?)');
				
				$stmt->bindValue(1, intval($this->Id), PDO::PARAM_INT);
				$stmt->bindValue(2, $this->Name, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->LongDescription, PDO::PARAM_STR);
				$stmt->bindValue(4, $this->Category->Id, PDO::PARAM_INT);

				$stmt->execute();
			}
		}
		
		// Loads the primary image for the current instance
		function GetPrimaryMediaItem(){
			
			if($this->mediaItems == null){
				$this->mediaItems = MediaItem::LoadByItemId($this->Id);
			}
			
			$primary = null;
			
			// Find the primary image
			foreach($this->mediaItems as $item){
				if($item->IsPrimary){
					$primary = $item;
					break;
				}
			}
			
			if($primary == null){
				// No primary is set
				if(count($this->mediaItems) > 0){
					// MediaItems are available, returning first one
					return $this->mediaItems[0];
				}
			}
			else{
				return $primary;
			}
			
			//No primary set and none available
			return null;
		}
		
		// Loads a collection of images for the current instance
		function GetAllMediaItems(){
			
			if($this->mediaItems == null){
				$this->mediaItems = MediaItem::GetAllByItemId($this->Id);
			}
			
			return $this->mediaItems;
				
		}
			
	}
?>