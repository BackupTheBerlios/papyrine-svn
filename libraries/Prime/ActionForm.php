<?php
class ActionForm {

	function __construct(){}
	
	public function reset( ActionMapping $map ){
		$attributes = get_class_vars( get_class( $this ) );
		foreach( $attributes as $key=>$attr ){
			$method = "set".$attr;
			$this->$method();
		}
	}
	
	public function validate( ActionMapping $map ){
		return true;
	}
}
?>
