<?php

	function smarty_function_blogs_getblogs ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'blogs';

		$blogs = BlogsPlugin;
		$smarty->assign ($params['assign'], $blogs->GetPosts());
	}
?>
