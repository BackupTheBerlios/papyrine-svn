<?php

	// Security
	ini_set ("register_globals", off);
	ini_set ("allow_url_fopen",  off);

	/**
	 * Autoload classes as needed.
	 * FIXME: Much better loading code!
	 */
	function __autoload ($class)
	{
		ini_set ("include_path", "." . join (array (
			'/var/www/localhost/htdocs/papyrine/libraries/',
			'/var/www/localhost/htdocs/papyrine/libraries/smarty/',
			'/var/www/localhost/htdocs/papyrine/classes/',
			'/var/www/localhost/htdocs/papyrine/plugins/database-sqlite/'
			), ":")
		);

		if (!@require_once ($class . '.class.php'))
			require_once ($class . '.php');
	}

	$papyrine = new Papyrine;

	switch ($_REQUEST["route"]) 
	{
/*		case "archive_individual":
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
*/
		case "admin":
			$papyrine->display ('admin/admin.html');
        	break;

		case "admin_post":
			$papyrine->display ('admin/post.html');
        	break;

		case "admin_plugins":
			$papyrine->display ('admin/plugins.html');
        	break;

		case "admin_users":
			if (isset ($_POST ["Submit"]))
			{
				$papyrine->CreateUser (1,
					$_POST ["nickname"],
					$_POST ["password"],
					$_POST ["firstname"],
					$_POST ["lastname"],
					$_POST ["email"]
				);
			}
			$papyrine->display ('admin/users.html');
        	break;

		case "frontpage":
		default:
			$papyrine->display ('default/frontpage.html');
			break;
	}

?>
