<?php
require_once "PrimeException.php";

class IllegalArgumentException extends PrimeException {
	function __construct( $msg = null ){
		parent::__construct( $msg );
	}
}
?>