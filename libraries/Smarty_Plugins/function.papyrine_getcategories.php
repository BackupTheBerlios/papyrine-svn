<?php

	function smarty_function_papyrine_getcategories ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'categories';

		$smarty->assign (
			$params['assign'], 
			Papyrine::Objects2Array ($smarty->GetCategories ())
		);
	}
?>
