<?php
	class User extends AbstractBase{
		public $DateCreated;
		public $FirstName;
		public $LastName;
		public $Email;
		public $UserName;
		public $BillingAddress;
		
		function User(){ }
		
		function User($id){
			$this->Load($id);
		}
		
		function User($firstName, $lastName, $email, $userName, $address){
			$this->FirstName = $firstName;
			$this->LastName = $lastName;
			$this->Email = $email;
			$this->UserName = $userName;
			$this->BillingAddress = $address;
		}
		
		function Save(){
			
			$actionMode = $this->IsNew() ? "insert" : "update";
			
			
			
		}
		
		function Load(){
			
		}
		
		
	}	
?>