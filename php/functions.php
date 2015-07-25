<?php

	// NUMBER PARSER HELPER
	function tryParseToNull($stringVal, $typeStr){
		
		if(strtolower($typeStr) == "int"){
			//Integer	
			return is_numeric($stringVal) ? intval($stringVal) : null;	
		}
		else if(strtolower($typeStr) == "float"){
			//Float
			return is_numeric($stringVal) ? floatval($stringVal) : null;		}
		else{
			return null;
		}
	}
	
	//STRING SHORTCUT
	function isNullOrEmpty($string){
		
		if($string == null)
			return true;
		if($string == "")
			return true;
		if(trim($string) == "")
			return true;
			
		return false;
	}
	
	// LOGIN & VALIDATION HELPERS
	function isValidFormPwd($pwd){
		
		$pwdPattern = "/^(?=(.*?[0-9]){2,2})(?=.*?[a-zA-Z]).{8,}$/";	
		return !preg_match($pwdPattern, $pwd) ? false : true;
	}
	
	function isValidFormEmail($email){
		
		$emailPattern = "/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/";
		
		return !preg_match($emailPattern, $email) ? false : true;
	}
	
	
	
?>
