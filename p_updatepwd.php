<?php
	
	require_once("includes/requirebundle.php");
	
	if(is_null($_SESSION[SESSION_USER_KEY])){
		header("Location: index.php");
		exit();
	}
	else{
		$p1 = trim($_POST['pwd1']);
		$p2 = trim($_POST['pwd2']);
		
		if(isNullOrEmpty($p2) || isNullOrEmpty($p1)){
			header("Location: myaccount.php?error=1");
				exit();
		}
		
		// check password match
		if($p1 != $p2){
			header("Location: myaccount.php?error=1");
			exit();
		}
		
		//OK to update from this point
		$password = password_hash($p1, PASSWORD_DEFAULT);
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		$con = new PDO($conStr, DB_USER, DB_PWD);	
		$stmt = $con->prepare('CALL User_UpdatePwd(?,?)');
		$stmt->bindValue(1, intval($_SESSION[SESSION_USER_KEY]->Id, PDO::PARAM_INT));
		$stmt->bindValue(2, strval($password), PDO::PARAM_STR);
		
		$stmt->execute();
		
		header("Location: myaccount.php?success=1");
		exit();
	}
?>