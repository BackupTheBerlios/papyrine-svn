<?php

	function smarty_modifier_bbcode ($text) 
	{
		$bbcode = new BBCodePlugin;
		return $bbcode->Convert ($text);
	}

?>
