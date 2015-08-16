<?php
	require_once("includes/requirebundle.php");
	
		
	$temp = new MiniTemplator;
	$load = $temp->readTemplateFromFile("html/tmpl-index.html");
	
	if(!$load)
		die ("Loading template has failed!");
		
	loadPage($temp);
	$temp->generateOutput();
	
	function loadPage(&$temp){
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		$con = new PDO($conStr, DB_USER, DB_PWD);
		
		$stmt = $con->prepare('CALL Search_GetResults(?)');
		$stmt->bindValue(1, '', PDO::PARAM_STR);
		$stmt->execute();
		
		$temp->addBlock("tableHeader");
		
		while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			$temp->setVariable("l_id", $record['ListingId']);
			$temp->setVariable("l_end", strval($record['EndDate'])); 
			$temp->setVariable("i_name", $record['ItemName']);
			$temp->setVariable("c_name", $record['CategoryName']);
			
			$image = $record['MediaFileName'];
			
			if(!is_null($image)){
				$temp->setVariable("l_mid", MEDIA_ITEM_PATH . $image);
				$temp->addBlock("ListingImage");
			}
			else{
				$temp->addBlock("NoImage");
			}
	
			$temp->addBlock("tableRow");
		}
		
		$temp->addBlock("tableFooter");
			
	}
	
?>