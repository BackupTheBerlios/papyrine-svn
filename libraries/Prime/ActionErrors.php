<?php
class ActionErrors {
	
	private $_errors = array();
	
	public function add( ActionError $error ){
		if( !$this->contains( $error ) ){
			$this->_errors[] = $error;
		}
	}
	
	public function contains( $error ){
		foreach( $this->_errors as $key=>$value ){
			if( $value == $error ){
				return true;
			}
		}
		return false;
	}
	
	public function getErrors(){
		return $this->_errors;
	}
	
	public function raise(){
		echo ( sizeof( $this->_errors ) > 0 ) ? "<ul>" : "";
		foreach( $this->_errors as $key=>$value ){
			echo "<li>" . $value->getError() . "</li>";
		}
		echo ( sizeof( $this->_errors ) > 0 ) ? "</ul>" : "";
	}
}
?>