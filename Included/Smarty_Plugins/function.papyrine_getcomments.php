<?php

	function smarty_function_papyrine_getcomments ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'comments';

		// Require an id.
		if (empty ($params['entry']))
			$smarty->trigger_error ('papyrine_getcomments: entry is a required parameter');

		$entry = $smarty->GetEntry ($params['entry']);

		$smarty->assign (
			$params['assign'], 
			Papyrine::Objects2Array ($entry->GetComments ())
		);
	}
?>
