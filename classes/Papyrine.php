<?php

/**
 * Papyrine is a weblogging system built using PHP5.
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
 * The central Papyrine object, hands database initialization.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
final class Papyrine extends Savant2
{
	public $database;

	function __construct ()
	{
		// Choose DB from config
		parent::__construct();
		$this->addPath ('template', BASE . 'templates/');
	}

	static function singleton()
	{
		return new Papyrine;
	}

	public function getPlugins()
	{
		$output = array();
		$it = new DirectoryIterator( BASE . 'plugins/' );

		while( $it->valid() )
		{
			$xml_path = $it->getPath() . $it->getFilename() . "/plugin.xml";
			if ( $it->isReadable() && $it->isDir() && file_exists( $xml_path ) )
				$output [] = new PapyrinePlugin( $xml_path );

			$it->next();
		}

		return $output;
	}

	public function CreateBlog ($title)
	{
		return $this->database->CreateBlog ($title);
	}

	public function CreateUser ($password, $firstname, $lastname, $email)
	{
		return $this->database->CreateUser ($password, $firstname, $lastname, 
		                                    $email);
	}

	public function GetUser ($id)
	{
		return $this->database->GetUser ($id);
	}

	public function GetUsers ($as_array = false)
	{
		return $this->database->GetUsers ($as_array);
	}

	public function GetBlog ($id)
	{
		return $this->database->GetBlog ($id);
	}

	public function GetEntries ($status, $limit = 10, $frontpage = true)
	{
		return $this->database->GetEntries ();
	}
}

?>
