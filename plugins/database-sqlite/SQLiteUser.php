<?php

/**
 * SQLitePapyrineBlog is a SQLite implementation of the PapyrineBlog class.
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
 * Provides the functionality required by PapyrineBlog using a SQLite database.
 */
class SQLiteUser extends SQLiteAbstraction implements PapyrineUser
{
	const TABLE = 'papyrine_users';

	function __construct ($id)
	{
		$this->_fetchSQL = sprintf (
			" SELECT *      " .
			" FROM %s WHERE " .
			" id = %s       " .
			" LIMIT 1       " ,
			self::TABLE,
			$id
		);

		$this->_deleteSQL = sprintf (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			self::TABLE,
			$id
		);
	}

	/**
	 * Create a new blog.
	 *
	 * @return integer
	 */
	public static function create ($email, $name, $password)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s           " .
			" (email, name, password) " .
			"VALUES                   " .
			" ('%s', '%s', '%s')      " ,
			self::TABLE,
			sqlite_escape_string ($email),
			sqlite_escape_string ($name),
			sqlite_escape_string (md5 ($password))
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}

	public static function authenticate ($id, $password)
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT COUNT(*) AS num " .
			" FROM %s                " .
			" WHERE id = %s          " .
			" AND password = %s      " ,
			self::TABLE,
			$id,
			$password
		);

		$result = $papyrine->database->connection->query ($sql);

		return (sqlite_fetch_single ($result) > 0);
	}

	public static function emailExists ($email)
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT COUNT(*) AS num " .
			" FROM %s                " .
			" WHERE email = '%s'     " ,
			self::TABLE,
			$email
		);

		$result = $papyrine->database->connection->query ($sql);

		return (sqlite_fetch_single ($result) > 0);		
	}
}

?>
