<?php

	function smarty_function_papyrine_getuser ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'user';

		// Require an id.
		if (empty ($params['id']))
			$smarty->trigger_error ('papyrine_getuser: id is a required parameter');

		$user = $smarty->GetUser ($params['id']);

		$smarty->assign (
			$params['assign'], 
			$user->ToArray ()
		);
	}
?>
