<?php

	function smarty_function_papyrine_getmodifiers ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'modifiers';

		$smarty->assign (
			$params['assign'], 
			$smarty->GetModifiers ()
		);
	}
?>
