<?php

	function smarty_modifier_wiki ($text) 
	{
		$wiki = new WikiPlugin;
		return $wiki->Convert ($text);
	}

?>
