<?php

	function smarty_function_delicious_gettags ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'tags';

		require_once "delicious.conf";
		require_once "DeliciousData.class.php";

		$delicious = DeliciousMethods ($user, $pass);
		$tags      = $delicious->getTags ();

		$smarty->assign ($params['assign'], $tags);
	}
?>
