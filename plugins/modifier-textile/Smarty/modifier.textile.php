<?php

	function smarty_modifier_textile ($text) 
	{
		return TextilePlugin::ModifyText ($text);
	}

?>
