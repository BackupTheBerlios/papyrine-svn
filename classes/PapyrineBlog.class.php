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

		require_once '/var/www/localhost/htdocs/papyrine/plugins/database-sqlite/SQLiteDatabasePlugin.class.php';

		$db = new SQLiteDatabasePlugin;

/**
 * Decribes a Papyrine blog.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineBlog
{
	function __get ($var)
	{
		global $papyrine;

		if ($var == 'title')
		{
		}
	}
	/**
	 * Create a new blog.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param string $title New blog's title.
	 * @return integer
	 * @uses PapyrineDatabasePlugin::Blog_Create
	 */
	public static function Create ($title)
	{
		global $papyrine;

		return $papyrine->database->Blog_Create ($title);
	}

	/**
	 * Delete the blog.
	 *
	 * @uses PapyrineDatabasePlugin::Blog_Delete
	 */
	public function Delete ()
	{
		global $papyrine;

		return $papyrine->database->Blog_Delete ($this->id);
	}
}

?>
