<?php

	function smarty_function_papyrine_getcomment ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'comment';

		// Require an id.
		if (empty ($params['id']))
			$smarty->trigger_error ('papyrine_getcomment: id is a required parameter');

		$comment = $smarty->GetComment ($params['id']);

		$smarty->assign (
			$params['assign'], 
			$comment->ToArray ()
		);
	}
?>
