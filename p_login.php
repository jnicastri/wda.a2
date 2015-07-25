<?php
	
	require_once("includes/requirebundle.php");
	
		
	$keyedUname = trim($_POST['uNameTb']);
	$keyedPwd = trim($_POST['pwdTb']);
	
	if(!empty($keyedUname) && !empty($keyedPwd)){
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		$con = new PDO($conStr, DB_USER, DB_PWD);	
		$stmt = $con->prepare('CALL Login_Get(?,@id,@pwd)');
		$stmt->bindValue(1, strval($keyedUname), PDO::PARAM_STR);
		
		$stmt->execute();
		
		$returnedId = $con->query("SELECT @id")->fetchAll(PDO::FETCH_ASSOC);
		$returnedPwd = $con->query("SELECT @pwd")->fetchAll(PDO::FETCH_ASSOC);
			
		$returnedId = $returnedId[0]['@id'];
		
		if(!is_null($returnedId)){
			if(password_verify($keyedPwd, $returnedPwd[0]['@pwd'])){

				$user = User::LoadById($returnedId);
				$_SESSION[SESSION_USER_KEY] = $user;
				
				if(isset($_SESSION[SESSION_LOGIN_ERROR]))
					unset($_SESSION[SESSION_LOGIN_ERROR]); 
					
				header("Location: MyAuctionSummary.php");
			}
			else{
				$_SESSION[SESSION_LOGIN_ERROR] = "Invalid Password";
				header("Location: login.php");
			}
		}
		else{
			$_SESSION[SESSION_LOGIN_ERROR] = "Invalid Username";
			header("Location: login.php");
		}	
	}
	else{
		header("Location: login.php");
	}
?>