<?php
session_start();
require_once "Request.php";
require_once "PrimeConfiguration.php";
require_once "BeanUtils.php";
require_once "ActionForm.php";

class Controller extends PrimeConfiguration {
	
	private $_request = null;
	
	function __construct(){
		$this->_request = ( !array_key_exists( "PrimeRequest", $_SESSION ) || !empty( $_SESSION["PrimeRequest"] ) ) ? new Request( Request::REQUEST ) : unserialize( $_SESSION["PrimeRequest"] );
		parent::__construct();
		$this->run();
	}
	
	function __destruct(){
		$_SESSION["PrimeRequest"] = serialize( $this->getRequest() );
	}
	
	public function getRequest(){
		return $this->_request;
	}
	
	public function run(){
		reset( $_GET );
		$map = ( sizeof( $_GET ) > 0 ) ? $this->getAction( key( $_GET ) ) : $this->getWelcome();
		if( $map->hasInput() ){
			$this->loadForward( $map->getInputForward(), $this->getRequest() );
		}else{
			$form  = new ActionForm();
			$fname = $map->getFormName();
			if( !empty( $fname ) ){
				//generate form class
				$form = $this->getForm( $map->getFormName() );
				//populate form class
				BeanUtils::populate( $_REQUEST, $form );
				//validate if necessary
				if( $map->getValidate() )
					if( !$form->validate( $map ) ){
						?><script>history.go(-1);</script><?php
						exit;
					}
			}
			
			//require the class file if not declared
			if( !class_exists( $map->getClass() ) ){
				if( file_exists( $map->getPath() ) )
					require_once $map->getPath();
				else
					throw new FileNotFoundException( $map->getPath() );
			}
			//get the class name in mapping
			$class = $map->getClass();
			//create the class
			$action = new $class;
			//execute & forward
			$forward = $action->execute( $map, $form, $this->getRequest() );
			if( $forward instanceof ActionForward ){
				$this->loadForward( $forward, $this->getRequest() );
			}
		}
	}
	
	private function loadForward( ActionForward $forward, Request $req ){
		ob_start();
		require_once $forward->getPath();
		$bufferedForward = ob_get_contents();
		ob_end_clean();
		echo $bufferedForward;
	}
	
	
}
?>
