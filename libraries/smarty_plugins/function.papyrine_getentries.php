<?php

	function smarty_function_papyrine_getentries ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'entries';

		if (empty ($params['limit']))
			$params['limit'] = 10;

		if (empty ($params['status']))
			$params['status'] = 2;

		if (empty ($params['frontpage']))
			$params['frontpage'] = false;

		if (empty ($params['category']))
			$entries = $smarty->GetEntries ($params['status'], $params['limit'], $params['frontpage']);
		else {
			$category = $smarty->GetCategory ($params['category']);
			$entries  = $category->GetEntries ($params['limit']);
		}

		$smarty->assign (
			$params['assign'], 
			Papyrine::Objects2Array ($entries)
		);
	}
?>
