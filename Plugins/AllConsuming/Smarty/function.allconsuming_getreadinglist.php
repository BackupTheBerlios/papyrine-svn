<?php

	function smarty_function_allconsuming_getreadinglist ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'readinglist';

		$allconsuming = AllConsumingPlugin;
		$smarty->assign ($params['assign'], $allconsuming->GetBooks());
	}
?>
