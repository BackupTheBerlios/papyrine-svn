<?php
require_once "PrimeException.php";

class StringIndexOutOfBounds extends PrimeException {
	function __construct( $msg = null ){
		parent::__construct( $msg );
	}
}
?>