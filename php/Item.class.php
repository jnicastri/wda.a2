<?php
	require_once("includes/classrequires.php");
	
	class Item extends AbstractBase{
		public $DateCreated;
		public $Name;
		public $LongDescription;
		public $Category;
		
		function __construct(){ }
		
		//Static Loaders
		static function LoadById($id){
			$loadItem = new Item();
			$loadItem->Load($id);
			
			return $loadItem;
		}
		
		static function GetNew($name, $desc, $catId){
			$newItem = new Item();
			
			$newItem->Name = $name;
			$newItem->LongDescription = $desc;
			$newItem->Category = Category::LoadById($catId);
			
			$newItem->Save();
		}
		
		//Instance Functions
		function Load($id){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Item_Load(?)');
			
			$stmt->bindValue(1, $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$this->Id = intval($record['Id']);
			$this->Name = $record['Name'];
			$this->LongDescription = $record['LongDescription'];
			$this->DateCreated = $record['DateCreated'];
			
			$this->Category = new Category();
			$this->Category->Id = $record['CatId'];
			$this->Category->Name = $record['CatName'];
			$this->Category->Description = $record['CatDesc'];
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
				
				$this->Id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
				$this->DateCreated = $con->query("SELECT @createdDate")->fetchAll(PDO::FETCH_ASSOC);
			}
			else{
				
				$stmt = $con->prepare('CALL Item_Update(?,?,?,?)');
				
				$stmt->bindValue(1, $this->Id, PDO::PARAM_INT);
				$stmt->bindValue(2, $this->Name, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->LongDescription, PDO::PARAM_STR);
				$stmt->bindValue(4, $this->Category->Id, PDO::PARAM_INT);

				$stmt->execute();
			}
		}
			
	}
?>