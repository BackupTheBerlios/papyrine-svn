<?php 
require_once "ActionConfig.php";

class ActionMapping extends ActionConfig {
	
	function __construct(){
		parent::__construct();
	}
	
	public function findForward( $name ){
		if( !empty( $name ) ){
			if( $this->getForward() instanceof ActionForward && $this->getForward()->getName() == $name ){
				return $this->getForward();
			}else{
				foreach( $this->getForwards() as $key=>$forward ){
					if( $forward->getName() == $name ){
						return $forward;
					}
				}
			}
			return null;
		}else
			throw new EmptyStringException();
	}
	
	public function findForwards(){
		$names = array();
		foreach( $this->getForwards() as $key=>$forward ){
			if( $forward instanceof ActionForward ){
				$names[] = $forward->getName();
			}
		}
		return $names;
	}
	
	public function getInputForward(){
		return new ActionForward( "input", $this->getInput() );
	}
}
?>