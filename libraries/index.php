<?php

	require_once ('Classes/Papyrine.class.php');

	$smarty = new Papyrine;

	switch ($_REQUEST["route"]) 
	{
		case "archive_individual":
			$smarty->display ('archive_individual.html');
        	break;
		case "archive_daily":
			$smarty->display ('archive_daily.html');
        	break;
		case "archive_monthly":
			$smarty->display ('archive_monthly.html');
        	break;
		case "archive_yearly":
			$smarty->display ('archive_yearly.html');
        	break;
		case "xmlrpc":
			$smarty->display ('xmlrpc.html');
        	break;

		case "category":
			$smarty->display ('category.html');
        	break;

		case "feed_recent":
			$syndicator = handle_feed_template ($_REQUEST ["version"]);
			$smarty->display ($syndicator->GetRecentFeedTemplate ());
        	break;
		case "feed_category":
			$syndicator = handle_feed_template ($_REQUEST ["version"]);
			$smarty->display ($syndicator->GetCategoryFeedTemplate ());
        	break;

		case "frontpage":
		default:
			$smarty->display ('frontpage.html');
			break;
	}

	function handle_feed_template ($type)
	{
		if ($syndicator = $smarty->GetSyndicator ($type))
		{
			$last_modified = gmdate ("D, d M Y H:i:s \G\M\T", C_LAST_MODIFIED);
			header ("Last-Modified: " . $last_modified, true);
			header ("Etag: " . md5($last_modified),     true);
			header ("Content-Type: " . $syndicator->mime-type, true);

			return $syndicator;
		}
	}

?>
