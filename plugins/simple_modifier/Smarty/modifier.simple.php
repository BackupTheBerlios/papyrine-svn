<?php

	function smarty_modifier_simple ($text) 
	{
		$simple = new SimplePlugin;
		return $simple->Convert ($text);
	}

?>
