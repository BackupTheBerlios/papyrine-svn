<?php
require_once "EmptyStringException.php";
require_once "PrimeConfiguration.php";

class ActionError {
	
	private $_error = "";
	
	function __construct( $error ){
		if( !empty( $error ) ){
			$this->_error = $error;
		}else{
			throw new EmptyStringException();
		}
	}
	
	public function getError(){
		return $this->_error;
	}
}
?>