<?php

/**
* 
* Example plugin for unit testing.
*
* @version $Id: Savant2_Plugin_example_extend.php,v 1.1 2004/06/21 14:01:57 pmjones Exp $
*
*/

$this->loadPlugin('example');

class Savant2_Plugin_example_extend extends Savant2_Plugin_example {
	
	var $msg = "Extended Example! ";
	
}
?>