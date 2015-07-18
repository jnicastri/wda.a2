<?php

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
	
	function isNullOrEmpty($string){
		
		if($string == null)
			return true;
		if($string == "")
			return true;
		if(trim($string) == "")
			return true;
			
		return false;
	}
?>
