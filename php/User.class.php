<?php
	require_once("includes/classrequires.php");
	
	class User extends AbstractBase{
		public $DateCreated;
		public $FirstName;
		public $LastName;
		public $Email;
		public $UserName;
		public $BillingAddress;
		
		function __construct(){ }
		
		static function LoadById($id){
			$loaduser = new User();
			$loaduser->Load($id);
			return $loaduser;
		}
		
		static function GetNew($firstName, $lastName, $email, $pwd, $userName, $address){
			
			$newUser = new User();
			
			$newUser->FirstName = $firstName;
			$newUser->LastName = $lastName;
			$newUser->Email = $email;
			$newUser->UserName = $userName;
			$newUser->BillingAddress = $address;
			
			$newUser->Save($pwd);
			
			return $newUser;
		}
		
		function Save($pwd =""){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			
			if($this->IsNew() && !IsNullOrEmpty($pwd)){

				$stmt = $con->prepare('CALL User_Insert(?,?,?,?,?,?,?,?,?,?, @id, @createdDate)');
				
				$stmt->bindValue(1, $this->FirstName, PDO::PARAM_STR);
				$stmt->bindValue(2, $this->LastName, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->Email, PDO::PARAM_STR);
				$stmt->bindValue(4, $this->UserName, PDO::PARAM_STR);
				$stmt->bindValue(5, $pwd, PDO::PARAM_STR);
				$stmt->bindValue(6, $this->BillingAddress->Line1, PDO::PARAM_STR);
				$stmt->bindValue(7, $this->BillingAddress->Line2, PDO::PARAM_STR);
				$stmt->bindValue(8, $this->BillingAddress->Suburb, PDO::PARAM_STR);
				$stmt->bindValue(9, $this->BillingAddress->State, PDO::PARAM_STR);
				$stmt->bindValue(10, $this->BillingAddress->Zip, PDO::PARAM_STR);
			
				$stmt->execute();
				
				$this->Id = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
				$this->DateCreated = $con->query("SELECT @createdDate")->fetchAll(PDO::FETCH_ASSOC);
			}
			else{
				
				$stmt = $con->prepare('CALL User_Update(?,?,?,?,?,?,?,?,?,?)');
				
				$stmt->bindValue(1, $this->Id, PDO::PARAM_INT);
				$stmt->bindValue(2, $this->FirstName, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->LastName, PDO::PARAM_STR);
				$stmt->bindValue(4, $this->Email, PDO::PARAM_STR);
				$stmt->bindValue(5, $this->UserName, PDO::PARAM_STR);
				$stmt->bindValue(6, $this->BillingAddress->Line1, PDO::PARAM_STR);
				$stmt->bindValue(7, $this->BillingAddress->Line2, PDO::PARAM_STR);
				$stmt->bindValue(8, $this->BillingAddress->Suburb, PDO::PARAM_STR);
				$stmt->bindValue(9, $this->BillingAddress->State, PDO::PARAM_STR);
				$stmt->bindValue(10, $this->BillingAddress->Zip, PDO::PARAM_STR);
			
				$stmt->execute();
			}
		}
		
		function Load($id){
			
			$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$stmt = $con->prepare('CALL User_Load(?)');
			
			$stmt->bindValue(1, $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$this->Id = intval($record['Id']);
			$this->FirstName = $record['FirstName'];
			$this->LastName = $record['LastName'];
			$this->Email = $record['Email'];
			$this->UserName = $record['DisplayUserName'];
			
			$userAddress = new AddressStruct();
			$userAddress->Line1 = $record['BillingAddressLine1'];
			$userAddress->Line2 = $record['BillingAddressLine2'];
			$userAddress->Suburb = $record['BillingAddressSuburb'];
			$userAddress->State = $record['BillingAddressState'];
			$userAddress->Zip = $record['BillingAddressZip']; 
			
			$this->BillingAddress = $userAddress;
		}
	}	
?>