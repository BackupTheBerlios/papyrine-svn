<?php
require_once "EmptyStringException.php";
require_once "FileNotFoundException.php";

class ActionForward {
	
	private $_name = "";
	private $_path = "";
	
	function __construct( $name, $path ){
		if( !empty( $path ) && !empty( $name ) ){
				$this->_name = $name;
				$this->_path = $path;
		}else
			throw new EmptyStringException();
	}
	
	public function getPath(){
		return $this->_path;
	}
	
	public function getName(){
		return $this->_name;
	}
}
?>