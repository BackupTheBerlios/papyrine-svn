<?php

	function smarty_function_breadcrumb_getcrumbs ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'crumbs';

		$smarty->assign ($params['assign'], BreadcrumbPlugin::BuildCrumbs ());
	}
?>
