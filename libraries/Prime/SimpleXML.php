<?php
require_once "EmptyStringException.php";
require_once "FileNotFoundException.php";

class SimpleXML {
	
	private $_xmlElement = "";
	
	function __construct( $filename = null ){
		if( !$filename == null ){
			$this->loadFile( $filename );
		}
	}
	
	public function loadFile( $filename ){
		if( file_exists( $filename ) ){
			$this->_xmlElement = simplexml_load_file( $filename );
		}else{
			throw new FileNotFoundException();
		}
	}
	
	public function loadString( $xml ){
		if( !empty( $xml ) ){
			$this->_xmlElement = simplexml_load_string( $xml );
		}else{
			throw new EmptyStringException();
		}
	}
	
	public function get(){
		return $this->_xmlElement;
	}
	
	public function contains( $name ){
		return ( $this->get()->$name instanceof simplexml_element ) ? true : false;
	}
	
	static function factory( $filename ){
		return new SimpleXML( $filename );
	}
}

?>