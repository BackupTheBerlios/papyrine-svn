<?php

	function smarty_function_delicious_getrecentposts ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'posts';

		require_once "delicious.conf";
		require_once "DeliciousData.class.php";

		$delicious = DeliciousMethods ($user, $pass);
		$posts     = $delicious->recentPosts (
			(!empty ($params['tag']) ? $params['tag'] : null),
			(!empty ($params['limit']) ? $params['limit'] : null)
		);

		$smarty->assign ($params['assign'], $posts);
	}
?>
