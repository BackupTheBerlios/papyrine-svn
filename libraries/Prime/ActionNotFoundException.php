<?php
require_once "PrimeException.php";

class ActionNotFoundException extends PrimeException {
	function __construct( $msg = null ){
		parent::__construct( $msg );
	}
}
?>