<?php

	require_once("includes/classrequires.php");
	
	class Category extends AbstractBase{
		
		public $Id;
		public $Name;
		public $Description;
		
		function __construct(){ }
		
		static function LoadById($id){
			$loadcat = new User();
			$loadcat->Load($id);
			return $loadcat;
		}
		
		static function GetAll(){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Category_GetAll()');
			
			$stmt->execute();
			$categories = Array();
			
			while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
				$cat = new Category();
				
				$cat->Id= $record['Id'];
				$cat->Name = $record['CategoryName']; 
				$cat->Description = $record['CategoryDescription']; 
					
				array_push($categories, $cat);
			}
			return $categories;
		}
		
		function Load($id){
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL Category_Load(?)');	
			
			$stmt->bindValue(1, $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$this->Id = intval($record['Id']);
			$this->Name = $record['CategoryName'];
			$this->Description = $record['CategoryDescription'];
		}
		
		function Save(){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			if($this->IsNew()){
				$stmt = $con->prepare('CALL Category_Insert(?,?, @id)');
				
				$stmt->bindValue(1, $this->Name, PDO::PARAM_STR);
				$stmt->bindValue(2, $this->Description, PDO::PARAM_STR);
				
				$stmt->execute();
				
				$this->Id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
			}
		}
	}
	
?>