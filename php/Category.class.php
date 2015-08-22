<?php

	require_once("includes/classrequires.php");
	
	class Category extends AbstractBase{
		
		public $Id;
		public $Name;
		public $Description;
		
		function __construct(){ }
		
		static function LoadById($id){
			$loadcat = new Category();
			$loadcat->Load($id);
			return $loadcat;
		}
		
		// Retreives all category from the DB
		static function GetAll(){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Category_GetAll()');
			
			$stmt->execute();
			$categories = Array();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$cat = new Category();
				
				$cat->Id= intval($record['Id']);
				$cat->Name = $record['CategoryName']; 
				$cat->Description = $record['CategoryDescription']; 
					
				array_push($categories, $cat);
			}
			return $categories;
		}
		
		// Loads the details of a single category from the DB
		function Load($id){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Category_Load(?)');	
			
			$stmt->bindValue(1, intval($id), PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$this->Id = intval($record['Id']);
			$this->Name = $record['CategoryName'];
			$this->Description = $record['CategoryDescription'];
		}
		
		// Save the current instance to the DB
		function Save(){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			if($this->IsNew()){
				$stmt = $con->prepare('CALL Category_Insert(?,?, @id)');
				
				$stmt->bindValue(1, $this->Name, PDO::PARAM_STR);
				$stmt->bindValue(2, $this->Description, PDO::PARAM_STR);
				
				$stmt->execute();
				
				$id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
				$this->Id = intval($id[0]["@id"]);
			}
		}
	}
	
?>