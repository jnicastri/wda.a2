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
		var_dump($user3);
	}
	catch(PDOException $exep){
		echo $exep->getMessage();
	}
	
	
	
	

	
?>
