<?php
require_once "PrimeException.php";

class ConfigurationException extends PrimeException {
	function __construct( $msg = null ){
		parent::__construct( $msg );
	}
}
?>