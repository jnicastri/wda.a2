<?php
	require_once("includes/classrequires.php");
	
	class CurrentSession{
		
		const SESSION_USER_KEY = "CurrentUserObject";
		
		static function UserLoginAction($userObj){
			$_SESSION[SESSION_USER_KEY] = $userObj;
		}
		
		static function CurrentUserNullable(){
			return isset($_SESSION[SESSION_USER_KEY]) ? $_SESSION[SESSION_USER_KEY] : null;
		}
		
		static function IsLoggedIn(){
			return isset($_SESSION[SESSION_USER_KEY]) ? true : false;
		}
		
		static function Exists($key){
			return isset($_SESSION[$key]) ? true : false;
		}
		
		static function Logout(){
			unset($_SESSION[SESSION_USER_KEY]);	
		} 
		
		static function AddEntry($key, $value){
			$_SESSION[$key] = $value;
		}
		
		static function RemoveEntry($key){
			unset($_SESSION[$key]);
		}
		
		static function RetrieveEntryNullable($key){
			return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
		}
	}	
	
?>