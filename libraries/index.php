<?php

	/**
	 * Autoload classes as needed.
	 * FIXME: Much better loading code!
	 */
	function __autoload ($class)
	{
		ini_set ("include_path", "." . join (array (
			'/var/www/localhost/htdocs/papyrine/libraries/',
			'/var/www/localhost/htdocs/papyrine/libraries/smarty/',
			'/var/www/localhost/htdocs/papyrine/classes/'
			), ":")
		);

		if (!@require_once ($class . '.class.php'))
			require_once ($class . '.php');
	}

	$papyrine = new Papyrine;

	switch ($_REQUEST["route"]) 
	{
		case "archive_individual":
			$papyrine->display ('archive_individual.html');
        	break;
		case "archive_daily":
			$papyrine->display ('archive_daily.html');
        	break;
		case "archive_monthly":
			$papyrine->display ('archive_monthly.html');
        	break;
		case "archive_yearly":
			$papyrine->display ('archive_yearly.html');
        	break;
		case "xmlrpc":
			$papyrine->display ('xmlrpc.html');
        	break;

		case "category":
			$papyrine->display ('category.html');
        	break;

		case "feed_recent":
			$syndicator = handle_feed_template ($_REQUEST ["version"]);
			$papyrine->display ($syndicator->GetRecentFeedTemplate ());
        	break;
		case "feed_category":
			$syndicator = handle_feed_template ($_REQUEST ["version"]);
			$papyrine->display ($syndicator->GetCategoryFeedTemplate ());
        	break;

		case "admin":
			$papyrine->CreateBlog ('test');
			$papyrine->display ('admin.html');
        	break;

		case "frontpage":
		default:
			$papyrine->display ('frontpage.html');
			break;
	}

?>
