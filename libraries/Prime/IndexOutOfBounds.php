<?php
require_once "PrimeException.php";

class IndexOutOfBounds extends PrimeException {
	function __construct( $msg = null ){
		parent::__construct( $msg );
	}
}
?>