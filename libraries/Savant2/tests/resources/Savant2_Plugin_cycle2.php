<?php

/**
* 
* Example plugin for unit testing.
*
* @version $Id: Savant2_Plugin_cycle2.php,v 1.1 2004/06/27 14:32:59 pmjones Exp $
*
*/

require_once 'Savant2/Plugin.php';

class Savant2_Plugin_cycle extends Savant2_Plugin {
	function plugin()
	{
		return "REPLACES DEFAULT CYCLE";
	}
}
?>