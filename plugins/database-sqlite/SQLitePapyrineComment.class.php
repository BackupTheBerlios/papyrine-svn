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
 * Decribes a Papyrine comment.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage SQLiteDatabasePlugin
 */
class SQLitePapyrineComment extends PapyrineComment
{
	const TABLE = 'papyrine_comments';
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
			" SELECT                                                     " .
			"  id, entry, body, owner_name, owner_email, status, created " .
			" FROM %s WHERE                                              " .
			"  id = %s                                                   " .
			" LIMIT 1                                                    " ,
			self::TABLE,
			$this->_id ["id"]
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
	 * Create a new comment.
	 */
	public static function Create (integer $entry, string $body, 
	                               string $owner_name, string $owner_email)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s                                           " .
			" (entry, body, owner_name, owner_email, status, created) " .
			"VALUES                                                   " .
			" (%s, '%s', '%s', '%s', %s, NOW())                       " ,
			self::TABLE,
			$entry,
			sqlite_escape_string ($body),
			sqlite_escape_string ($owner_name),
			sqlite_escape_string ($owner_email),
			0
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}

	public function GetID ()
	{
		return $this->__get ("id");
	}

	public function GetBody ()
	{
		return $this->__get ("body");
	}

	public function GetOwnerName ()
	{
		return $this->__get ("owner_name");
	}

	public function GetOwnerEmail ()
	{
		return $this->__get ("owner_email");
	}

	public function GetStatus ()
	{
		return $this->__get ("status");
	}

	public function GetCreated ()
	{
		return $this->__get ("created");
	}

	public function GetEntry ()
	{
		return new SQLitePapyrineEntry ($this->__get ("entry"));
	}

	public function SetBody (string $body)
	{
		return $this->__set ("body", $body);
	}

	public function SetOwnerName (string $name)
	{
		return $this->__set ("owner_name", $name);
	}

	public function SetOwnerEmail (string $email)
	{
		return $this->__set ("owner_email", $email);
	}

	public function SetStatus (integer $status)
	{
		return $this->__set ("status", $status);
	}

	/**
	 * Delete the category.
	 */
	public function Delete ()
	{
		global $papyrine;

		$sql1 = sprintf (
			" UPDATE %s SET           " .
			" comments = comments - 1 " .
			" WHERE id = %s           " .
			" LIMIT 1                 " ,
				SQLitePapyrineEntry::TABLE,
			$this->_id ["id"]
		);

		$papyrine->database->connection->unbufferedQuery ($sql1);

		$sql2 = sprintf (
			" DELETE FROM %s " .
			" AND id = %s    " .
			" LIMIT 1        " ,
			self::TABLE,
			$this->_id ["id"]
		);

		return $papyrine->database->connection->unbufferedQuery ($sql2);
	}
}

?>
