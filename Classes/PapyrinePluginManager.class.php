<?php

/**
 * Papyrine is a weblogging system built using PHP5 and Smarty.
 * Copyright (C) 2004 Thomas Reynolds
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @package Papyrine
 * @subpackage Classes
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrinePluginManager
{
	public static function Install (&$database, $file)
	{
		$directory = $this->DecompressPlugin ($file);
		$tar = new Archive_Tar ($file, true);
		$result = $tar->extract ('/Plugins/tmp/');

		$xml = simplexml_load_file ($directory . "about.xml");

		// if same or newer version exists, don't install
		// if older version exists, prompt to remove
		// RelaxRG validate

		return PapyrinePlugin::Create (
			$database,
			$this->name, 
			$this->description, 
			$this->version, 
			($plugin->ProvidesSmarty() ? $plugin->smarty : false), 
			($plugin->ProvidesTemplates() ? $plugin->templates : false), 
			($plugin->ProvidesModifiers() ? $plugin->modifiers : false)
		);
	}

	public static function Delete (PapyrinePlugin $plugin)
	{
		// Delete from database.
		$plugin->Delete ();

		// Delete from hard drive.

		// Clean up database files.
	}
}

?>
