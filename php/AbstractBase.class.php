<?php
	abstract class AbstractBase{
		public $Id;
		
		function IsNew(){
			return ($Id == null || $Id == "" || $Id < 0) ? true : false;
		}	
	}	
?>