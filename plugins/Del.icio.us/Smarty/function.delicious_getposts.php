<?php

	function smarty_function_blogs_getblogs ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'posts';

		require_once "delicious.conf";
		require_once "DeliciousData.class.php";

		$delicious = DeliciousMethods ($user, $pass);
		$posts     = $delicious->getPosts (
			(!empty ($params['tag']) ? $params['tag'] : false),
			(!empty ($params['date']) ? $params['date'] : false)
		);

		$smarty->assign ($params['assign'], $posts);
	}
?>
