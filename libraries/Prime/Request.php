<?php
require_once "IllegalArgumentException.php";
require_once "StringIndexOutOfBounds.php";
require_once "IndexOutOfBounds.php";

class Request {

	const SESSION = 1;
	const REQUEST = 2;
	
	private $_mode 			= 0;
	
	function __construct( $mode = Request::SESSION ){
		$this->setMode( $mode );
	}
	
	public function setMode( $mode ){
		if( $mode == Request::SESSION || $mode == Request::REQUEST ){
			$this->_mode = $mode;
		}else
			throw new IllegalArgumentException( "Expected Type: Request's Constant" );
	}
	
	public function getMode(){
		return $this->_mode;
	}
	
	public function setAttribute( $name, $value ){
		if( $this->getMode() == Request::SESSION )
			$_SESSION[$name] = $value;
		else
			$_REQUEST[$name] = $value;
	}
	
	public function getAttribute( $name ){
		if( $this->contains( $name ) ){
			if( $this->getMode() == Request::SESSION )
				return $_SESSION[$name];
			else
				return $_REQUEST[$name];
		}else
			throw new StringIndexOutOfBounds( "String: {$name}" );
	}
	
	public function getAttributeAt( $index ){
		if( is_int( $index ) ){
			$req = $this->getIndexedArray();
			if( array_key_exists( $index, $req ) )
				return $req[$index];
			else
				throw new IndexOutOfBounds( "Index: {$index}" );
		}else
			throw new IllegalArgumentException( "Type Expected: integer" );
	}
	
	public function getAttributeName( $index ){
		$req = ( $this->getMode() == Request::SESSION ) ? $_SESSION : $_REQUEST;
		$i = 0;
		foreach( $req as $name=>$value ){
			if( $i == $index )
				return $name;
			$i++;
		}
		throw new IndexOutOfBounds( "Index: {$index}" );
	}
	
	public function getAttributes(){
		return ( $this->getMode() == Request::SESSION ) ? $_SESSION : $_REQUEST;
	}
	
	private function getIndexedArray(){
		$req = ( $this->getMode() == Request::SESSION ) ? $_SESSION : $_REQUEST;
		$arr = array();
		foreach( $req as $key=>$value ){
			$arr[] = $value;
		}
		return $arr;
	}
	
	public function contains( $name ){
		$req = ( $this->getMode() == Request::SESSION ) ? $_SESSION : $_REQUEST;
		foreach( $req as $key=>$value ){
			if( $key == $name )
				return true;
		}
		return false;
	}
	
	public function removeAttribute( $name ){
		if( $this->contains( $name ) ){
			if( $this->getMode() == Request::SESSION )
				session_unregister( $name );
			else
				unset( $_REQUEST[$name] );
		}
	}
	
	public function destroy(){
		if( $this->getMode() == Request::SESSION )
			session_destroy();
		else
			unset( $_REQUEST );
	}
	
	public function length(){
		return sizeof( $this->getAttributes() );
	}
}
?>