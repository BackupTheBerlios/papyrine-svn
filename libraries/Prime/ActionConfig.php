<?php
require_once "FileNotFoundException.php";
require_once "EmptyStringException.php";
require_once "IllegalArgumentException.php";

class ActionConfig {


	private $_input 	= null;
	private $_parameter = "";
	private $_name 		= "";
	private $_formName	= "";
	private $_forward 	= null;
	private $_forwards	= array();
	private $_path		= "";
	private $_roles		= "";
	private $_roleNames = array();
	private $_validate 	= false;
	private $_class		= "";

	function __construct(){}
	
	public function setInput( $input ){
		if( strlen( $input ) > 0 && !empty( $input ) ){
			if( file_exists( $input ) || is_dir( $input ) ){
				$this->_input = (string) $input;
			}else
				throw new FileNotFoundException(null,$input);
		}else
			$this->_input = null;
	}
	
	public function setParameter( $parameter ){
		$this->_parameter = (string) $parameter;
	}
	
	public function setName( $name ){
		if( !empty( $name ) ){
			$this->_name = (string) $name;
		}else
			throw new EmptyStringException();
	}
	
	public function setFormName( $name ){
		$this->_formName = (string) $name;
	}
	
	public function setForward( ActionForward $forward ){
		$this->_forward = $forward;
	}
	
	public function setForwards( $forwards ){
		if( is_array( $forwards ) ){
			$af = array();
			foreach( $forwards as $key=>$forward ){
				if( $forward instanceof ActionForward )
					$af[] = $forward;
			}
			$this->_forwards = ( sizeof( $af ) > 0 ) ? $af : $this->_forwards;
		}else
			throw new IllegalArgumentException( "Expected Type: array" );
	}
	
	public function setPath( $path ){
		if( !empty( $path ) ){
			$this->_path = (string) $path;
		}else if( $path != null )
			throw new EmptyStringException();
	}
	
	public function setRoles( $roles ){
		$this->_roles = (string) $roles;
		$this->setRoleNames( $this->getRoles() );
	}
	
	private function setRoleNames( $roles ){
		$this->_roleNames = split( ',', $roles );		
	}
	
	public function setValidate( $validate = true){
		if( is_bool( $validate ) ){
			$this->_validate = $validate;
		}else
			throw new IllegalArgumentException( "Expected Type: bool" );
	}
	
	public function setClass( $class ){
		$this->_class = (string) $class;
	}
	
	
	public function getInput(){
		return $this->_input;
	}
	
	public function getParameter(){
		return $this->_parameter;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getFormName(){
		return $this->_formName;
	}
	
	public function getForward(){
		return $this->_forward;
	}
	
	public function getForwards(){
		return $this->_forwards;
	}
	
	public function getPath(){
		return $this->_path;
	}
	
	public function getRoles(){
		return $this->_roles;
	}
	
	public function getRoleNames(){
		return $this->_roleNames;
	}
	
	public function getValidate(){
		return $this->_validate;
	}	
	
	public function getClass(){
		return $this->_class;
	}
	
	public function hasInput(){
		return $this->getInput() ? true : false;
	}
}
?>