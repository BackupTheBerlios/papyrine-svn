<?php

	function smarty_modifier_textile ($text) 
	{
		$textile = new TextilePlugin;
		return $textile->Convert ($text);
	}

?>
