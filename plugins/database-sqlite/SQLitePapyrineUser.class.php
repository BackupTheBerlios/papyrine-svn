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
class SQLitePapyrineUser implements PapyrineUser
{
	const TABLE = 'papyrine_users';
	private $_id;
	private $_data = false;

	function __construct (integer $id)
	{
		$this->_id = $id;
	}

	private function _Populate ()
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT id, password, firstname, lastname, email " .
			" FROM %s WHERE                                   " .
			" id = %s                                         " .
			" LIMIT 1                                         " ,
			self::TABLE,
			$this->_id
		);

		$this->_data = $papyrine->database->connection->arrayQuery (
			$sql,
			SQLITE_ASSOC
		);
	}

	function __get ($var)
	{
		if ($this->_data == false)
			$this->_Populate ();

		if (isset ($this->_data[$var]))
			return $this->_data [$var];
	}

	function __set ($var, $val)
	{
		//
	}

	/**
	 * Create a new blog.
	 *
	 * @param string $title New blog's title.
	 * @return integer
	 */
	public static function Create ($password, $firstname, $lastname, $email)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s                          " .
			" (password, firstname, lastname, email) " .
			"VALUES                                  " .
			" ('%s', '%s', '%s', '%s', '%s')         " ,
			self::TABLE,
			sqlite_escape_string ($password),
			sqlite_escape_string ($firstname),
			sqlite_escape_string ($lastname),
			sqlite_escape_string ($email)
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}

	public function GetID ()
	{
		return $this->__get ("id");
	}

	public function GetPassword ()
	{
		return $this->__get ("password");
	}

	public function GetFirstName ()
	{
		return $this->__get ("firstname");
	}

	public function GetLastName ()
	{
		return $this->__get ("lastname");
	}

	public function GetEmail ()
	{
		return $this->__get ("email");
	}

	public function SetPassword ($password)
	{
		return $this->__set ("password", $password);
	}

	public function SetFirstName ($firstname)
	{
		return $this->__set ("firstname", $firstname);
	}

	public function SetLastName ($lastname)
	{
		return $this->__set ("lastname", $lastname);
	}

	public function SetEmail ($email)
	{
		return $this->__set ("email", $email);
	}

	public static function Authenticate ($id, $password)
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

	/**
	 * Delete the blog.
	 */
	public function Delete ()
	{
		global $papyrine;

		$sql = sprintf (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			self::TABLE,
			$this->_id
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}
}

?>
