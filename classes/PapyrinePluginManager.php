<?php

	class PapyrinePluginManager
	{
		const CONFIG_XML = "/var/www/localhost/htdocs/papyrine/data/config.xml";

		public function uploadFile ($file)
		{
			$uploaded =
			"/var/www/localhost/htdocs/papyrine/data/plugins/" . $file['name'];

			if (!file_exists ($uploaded))
				move_uploaded_file ($file['tmp_name'], $uploaded);

			$this->installFromFile ($uploaded);
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

		public function installFromDirectory ($dir)
		{
//DOMDocument->relaxNGValidate
echo yyoooo;
			$xml = simplexml_load_file ($dir . "about.xml");
			$this->updateConfig ($xml->id, $xml->title, $xml->provides);

			// read about.xml
			// add to config.xml
		}

		public function updateConfig ($id, $title, $provides)
		{
			$config = new DOMDocument ();
			$config->load (self::CONFIG_XML);

			foreach ($provides as $provided)
			{
				if ($provided == 'database')
				{
					$node = $config->createElement ("database");

					$node->setAttribute ("id", $id);

					$config->createTextNode ($title);
					$node->appendChild ($node);

					echo $config->getElementById ($id);

					$config->documentElementappendChild ($node);
				}
			}

			print htmlspecialchars($config->saveXML());
			$config->save (self::CONFIG_XML);
		}

        public function getPlugins ()
        {
			$xml = simplexml_load_file ("/var/www/localhost/htdocs/papyrine/data/config.xml");
            return $xml->plugin;
        }
	}
?>
