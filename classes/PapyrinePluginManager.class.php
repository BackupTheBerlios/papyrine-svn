<?php

	class PapyrinePluginManager
	{
		function __construct ()
		{
			$xml = simplexml_load_file ("compress.bzip2://" . config);
		}

		public function installFromFile ($file)
		{
			//tar
			$this->installFromDirectory ($dir)
		}

		public function installFromDirectory ($dir)
		{
			// read about.xml
			// add to config.xml
		}
	}
?>
