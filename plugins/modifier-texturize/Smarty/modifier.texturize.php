<?php

	function smarty_modifier_texturize ($text) 
	{
		$texturize = new TexturizePlugin;
		return $texturize->Convert ($text);
	}

?>
