<?php

	function smarty_modifier_bbcode ($text) 
	{
		return BBCodePlugin::ModifyText ($text);
	}

?>
