<?php

	function smarty_modifier_wiki ($text) 
	{
		return WikiPlugin::ModifyText ($text);
	}

?>
