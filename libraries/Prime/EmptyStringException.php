<?php
require_once "PrimeException.php";

class EmptyStringException extends PrimeException {
	function __construct( $msg = null ){
		$msg = ( !empty( $msg ) ) ? $msg : "The string can't empty.";
		parent::__construct( $msg );
	}
}
?>