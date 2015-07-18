<?php
	print("User Retreival");
	try{
		require_once("includes/requirebundle.php");
	}
	catch(Exception $exc){
		print($exc->getMessage());
	}
	print("Requires Pass");
	
	try{
		$user3 = User::LoadById(3);
		$user4 = User::LoadById(4);
		$user5 = User::LoadById(5);
	}
	catch(PDOException $exep){
		echo $exep->getMessage();
	}
	
	$user3->FirstName = "Marshall";
	$user3->LastName = "Erikson";
	$user3->Email = "marshian@example.com";
	$user3->UserName = "Lawyered";
	$user3->BillingAddress->Line1 = "19 Park Ave";
	$user3->BillingAddress->Line2 = "Ground Floor";
	$user3->Save();
	
	$user4->FirstName = "Robyn";
	$user4->LastName = "Sherbatski";
	$user4->Email = "robyn@example.com";
	$user4->UserName = "Sparkles";
	$user4->BillingAddress->Line1 = "22 Rockafella";
	$user4->BillingAddress->Line2 = "Level 1";
	$user4->Save();
	
	$user5->FirstName = "Lily";
	$user5->LastName = "Aldren";
	$user5->Email = "lily@example.com";
	$user5->UserName = "lilypad";
	$user5->BillingAddress->Line1 = "19 Park Ave";
	$user5->BillingAddress->Line2 = "Ground Floor";
	$user5->Save();
	

	
?>