<?php
class BeanUtils {
	
	static public function copyProperties( $objFrom, $objTo ){
		$prop = get_class_vars( get_class( $objFrom ) );
		foreach( $prop as $name ){
			$set = "set".$name;
			$get = "get".$name;
			$objTo->$set( $objFrom->$get() );
		}
	}
	
	static public function populate( $dataArray, $inObj ){
		$methods = get_class_methods( get_class( $inObj ) );
		foreach( $dataArray as $name=>$value ){
			$set = "set".$name;
			if( in_array( $set, $methods ) )
				$inObj->$set($value);
		}
	}	
}
?>