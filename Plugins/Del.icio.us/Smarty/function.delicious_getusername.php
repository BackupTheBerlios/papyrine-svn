<?php

	function smarty_function_blogs_getusername ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'username';

		$delicious = DeliciousPlugin ();
		$smarty->assign ($params['assign'], $delicious->username);
	}
?>
