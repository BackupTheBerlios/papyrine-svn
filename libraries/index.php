<?php
	// Security
	ini_set ("register_globals", off);
	ini_set ("allow_url_fopen",  off);

	define ('BASE', '/var/www/localhost/htdocs/papyrine/');
	define ('SITE', 'http://localhost/papyrine/');

	/**
	 * Autoload classes as needed.
	 * FIXME: Much better loading code!
	 */
	$paths = array (
		BASE . 'libraries/Prime/',
		BASE . 'libraries/',
		BASE . 'classes/',
		BASE . 'classes/actions/'
	);

	ini_set ("include_path", join ($paths, ":"));

	function __autoload ($class)
	{
		require_once ($class . '.php');
	}

	$papyrine = new Papyrine;
	$control = new Controller;
?>
