<?php

/**
* 
* Example plugin for unit testing.
*
* @version $Id: Savant2_Plugin_example.php,v 1.1 2004/06/21 14:01:57 pmjones Exp $
*
*/

require_once 'Savant2/Plugin.php';

class Savant2_Plugin_example extends Savant2_Plugin {
	
	var $msg = "Example: ";
	
	function plugin()
	{
		echo $this->msg . "this is an example!";
	}
}
?>