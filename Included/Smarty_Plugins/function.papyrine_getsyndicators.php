<?php

	function smarty_function_papyrine_getsyndicators ($params, &$smarty) 
	{
		// Set default variable name.
		if (empty ($params['assign']))
			$params['assign'] = 'syndicators';

		if (empty ($params['type']))
			$smarty->trigger_error ('papyrine_getsyndicators: type is a required parameter');

		$syndicators = $smarty->GetSyndicators ();

		$output = array ();
		foreach ($syndicators as $syndicator)
		{
			if ($params['type'] == "category")
				$url = $syndicator->category_url;
			elseif ($params['type'] == "recent")
				$url = $syndicator->recent_url;

			$output[] = array (
				"url"       => $url,
				"title"     => $syndicator->title,
				"mime-type" => $syndicator->mime-type
			);
		}

		$smarty->assign ($params['assign'], $output);
	}
?>
