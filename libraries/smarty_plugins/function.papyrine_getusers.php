<?php

	function smarty_function_papyrine_getusers ($params, &$smarty) 
	{
		global $papyrine;

		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'users';

		$smarty->assign (
			$params['assign'], 
			$papyrine->GetUsers (true)
		);
	}
?>
