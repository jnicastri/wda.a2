<?php
	require_once("includes/requirebundle.php");
	
	if(!is_null($_SESSION[SESSION_USER_KEY])){
		header("Location: myaccount.php");
		exit();	
	}
	
	$fname = trim($_POST['firstNameTb']);
	$lname = trim($_POST['lastNameTb']);
	$email = trim($_POST['emailTb']);
	$uname = trim($_POST['nicknameTb']);
	$add1 = trim($_POST['add1Tb']);
	$add2 = trim($_POST['add2Tb']);
	$addSub = trim($_POST['addSubTb']);
	$addState = trim($_POST['addStateTb']);
	$addZip = trim($_POST['addZipTb']);
	$p1 = trim($_POST['pwd1']);
	$p2 = trim($_POST['pwd2']);
	
	// Check for empty values
	if(isNullOrEmpty($fname) ||
		isNullOrEmpty($lname) ||
		isNullOrEmpty($email) ||
		isNullOrEmpty($uname) ||
		isNullOrEmpty($add1) ||
		isNullOrEmpty($add2) ||
		isNullOrEmpty($addSub) ||
		isNullOrEmpty($addState) ||
		isNullOrEmpty($addZip) ||
		isNullOrEmpty($p1) || 
		isNullOrEmpty($p2)){
			header("Location: newsignup.php?error=1");
			exit();
		}
	
	//check email form
	if(!isValidFormEmail($email)){
		header("Location: newsignup.php?error=1");
		exit();
	}
	
	// check password match
	if($p1 != $p2){
		header("Location: newsignup.php?error=1");
		exit();
	}
	
	$password = password_hash($p1, PASSWORD_DEFAULT);
	
	$address = new AddressStruct();
	$address->Line1 = $add1;
	$address->Line2 = $add2;
	$address->Suburb = $addSub;
	$address->State = $addState;
	$address->Zip = $addZip;
	
	// commiting new user to DB here
	$newUser = User::GetNew($fname, $lname, $email, $password, $uname, $address);
	
	if(is_null($newUser)){
		header("Location: newsignup.php?error=1");
		exit();
	}
	else{
		// Store New user in session
		$_SESSION[SESSION_USER_KEY] = $newUser;
		header("Location: MyAuctionSummary.php");
		exit();
	}

?>