<?php

	function smarty_function_papyrine_getcategory ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'category';

		// Require an id.
		if (empty ($params['id']))
			$smarty->trigger_error ('papyrine_getcategory: id is a required parameter');

		$smarty->assign (
			$params['assign'], 
			$smarty->GetCategory ($params['id'])->ToArray ()
		);
	}
?>
