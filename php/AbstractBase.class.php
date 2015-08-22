<?php
	abstract class AbstractBase{
		// Defining common attributes - an Id to match the database entity ID,
		// and a simple is new check (checks if current instance has been written
		//to DB yet by checking the existence of an ID) 
		
		public $Id;
		
		function IsNew(){
			return ($Id == null || $Id == "" || $Id < 0) ? true : false;
		}	
	}	
?>