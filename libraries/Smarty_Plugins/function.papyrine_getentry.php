<?php

	function smarty_function_papyrine_getentry ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'entry';

		// Require an id.
		if (empty ($params['id']))
			$smarty->trigger_error ('papyrine_getentry: id is a required parameter');

		$entry = $smarty->GetEntry ($params['id']);

		$smarty->assign (
			$params['assign'], 
			$entry->ToArray ()
		);
	}
?>
