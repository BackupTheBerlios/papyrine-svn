<?php

	class PapyrinePluginFile
	{
		private $_file;
		private $_xml = false;

		function __construct ($file)
		{
			$this->_file = $file;
		}

		function validate ()
		{
//DOMDocument->relaxNGValidate
		}

		function __get ($var)
		{
        	if (!$_xml)
        	{
				$tar = new Archive_Tar ($file, 'bz2');
				$about =& $tar->extractInString ("about.xml");
				$this->_xml =& simplexml_load_string ($about);
			}

            return $this->_xml->$var;
		}

		public function installFromFile ($file)
		{
			require_once "/var/www/localhost/htdocs/papyrine/libraries/PEAR.php";
			require_once "/var/www/localhost/htdocs/papyrine/libraries/Archive_Tar.php";

			$tar = new Archive_Tar ($file, 'bz2');
			$about = $tar->extractInString ("about.xml");
			$xml = simplexml_load_string ($about);

			$dir = "/var/www/localhost/htdocs/papyrine/data/plugins/tmp/" . $xml->id . "/";
			if (!is_dir ($dir))
				$tar->extract ($dir);

			$this->installFromDirectory ($dir);
		}
	}
?>
