<?php
require_once "EmptyStringException.php";
require_once "ActionNotFoundException.php";
require_once "ConfigurationException.php";

require_once "SimpleXML.php";
require_once "ActionForward.php";
require_once "ActionMapping.php";

if( !class_exists( 'PrimeConfiguration' ) ){
	class PrimeConfiguration {
		
		private $_config = null;
		
		function __construct(){
			$this->getConfiguration();
		}
		
		private function getConfiguration(){
			if( $this->_config == null ){
				$this->_config = SimpleXML::factory( BASE . "libraries/Prime/prime.xml" )->get();
			}
			return $this->_config;
		}
		
		public function getForm( $name ){
			if( !empty( $name ) ){
				foreach( $this->getConfiguration()->forms->form as $form ){
					if( $form["name"] == $name ){
						if( !class_exists( $form["class"] ) )
							require_once $form["path"];
						$formClass = (string)$form["class"];
						return new $formClass();
					}
				}
				return null;
			}else{
				throw new EmptyStringException();
			}
		}
		
		public function getForward( $name ){
			if( !empty( $name ) ){
				for( $i = 0; $i<sizeof( $this->getConfiguration()->forwards->forward ); $i++ ){
					$forward = $this->getConfiguration()->forwards->forward[$i];
					if( $forward["name"] == $name ){
						return new ActionForward( $name, $forward["path"] );
					}
				}
				return null;
			}
		}
		
		public function getAction( $name ){
			if( !empty( $name ) ){
				foreach( $this->getConfiguration()->actions->action as $action ){
					if( $action["name"] == $name ){
						$map = new ActionMapping();
						try{
							$map->setInput( $action["input"] );
							$map->setClass( $action["class"] );
							$map->setName( $action["name"] );
							$map->setFormName( $action["form"] );
							if( $this->getForward( $action["forward"] ) )
								$map->setForward( $this->getForward( $action["forward"] ) );
							$map->setPath( $action["path"] );
							$map->setValidate( (bool) $action["validate"] );
							$map->setRoles( $action["roles"] );
							$map->setParameter( $action["parameter"] );
							$forwards = array();
							foreach( $action->forward as $key=>$forward ){
								$forwards[] = new ActionForward( $forward["name"], $forward["path"] );
							}
							$map->setForwards( $forwards );
							return $map;
						}catch( Exception $e ){
							throw new ConfigurationException( "Action: {$action['name']}" );
						}
					}
				}
				throw new ActionNotFoundException( "Action: {$name}" );
			}else{
				throw new EmptyStringException();
			}
		}
		
		public function getWelcome(){
			$welcome = $this->getConfiguration()->welcome;
			if( $welcome ){
				return $this->getAction( $welcome["name"] );
			}
			return null;
		}
	}
}
?>
