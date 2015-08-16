<?php
	require_once("includes/requirebundle.php");
	
	if(is_null($_SESSION[SESSION_USER_KEY])){
		header("Location: index.php");
		exit();
	}
	else{
		$fname = trim($_POST['firstNameTb']);
		$lname = trim($_POST['lastNameTb']);
		$email = trim($_POST['emailTb']);
		$uname = trim($_POST['nicknameTb']);
		$add1 = trim($_POST['add1Tb']);
		$add2 = trim($_POST['add2Tb']);
		$addSub = trim($_POST['addSubTb']);
		$addState = trim($_POST['addStateTb']);
		$addZip = trim($_POST['addZipTb']);
		
		if(isNullOrEmpty($fname) ||
			isNullOrEmpty($lname) ||
			isNullOrEmpty($email) ||
			isNullOrEmpty($uname) ||
			isNullOrEmpty($add1) ||
			isNullOrEmpty($add2) ||
			isNullOrEmpty($addSub) ||
			isNullOrEmpty($addState) ||
			isNullOrEmpty($addZip)){
				header("Location: myaccount.php?error=1");
				exit();
			}
			
		if(!isValidFormEmail($email)){
			header("Location: myaccount.php?error=1");
			exit();
		}
			
		// Everthing should be ok at this point - update user	
		$user = $_SESSION[SESSION_USER_KEY];
		
		$user->FirstName = $fname;
		$user->LastName = $lname;
		$user->Email = $email;
		$user->UserName = $uname;
		$user->BillingAddress->Line1 = $add1;
		$user->BillingAddress->Line2 = $add2;
		$user->BillingAddress->Suburb = $addSub;
		$user->BillingAddress->State = $addState;
		$user->BillingAddress->Zip = $addZip;
		
		// Commit to DB	
		$user->Save();
		
		// Replace Logged in user instance
		$_SESSION[SESSION_USER_KEY] = $user;
		header("Location: myaccount.php?success=1");
	}	
	
?>