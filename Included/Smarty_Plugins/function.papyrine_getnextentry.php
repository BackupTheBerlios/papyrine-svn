<?php

	function smarty_function_papyrine_getnextentry ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'next';

		// Require an id.
		if (empty ($params['id']))
			$smarty->trigger_error ('papyrine_getnextentry: id is a required parameter');

		$entry = $smarty->GetEntry ($params['id']);

		$smarty->assign (
			$params['assign'], 
			$entry->GetNext ()->ToArray ()
		);
	}
?>
