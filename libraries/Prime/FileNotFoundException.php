<?php
require_once "PrimeException.php";

class FileNotFoundException extends PrimeException {
	function __construct( $msg = null, $filename = null ){
		$msg = ( !empty( $msg ) ) ? $msg : "The specified file {$filename} is not found!";
		parent::__construct( $msg );
	}
}
?>