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
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage SQLiteDatabasePlugin
 */
class SQLitePapyrineBlog extends PapyrineBlog
{
	const TABLE = 'papyrine_blogs';
	private $_id;
	private $_data = false;

	function __construct ($id)
	{
		$this->_id = $id;
	}

	private function _Populate ()
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT id, title " .
			" FROM %s WHERE    " .
			" id = %s          " .
			" LIMIT 1          " ,
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
	public static function Create (string $title)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s " .
			" (title)       " .
			"VALUES         " .
			" ('%s')        " ,
			self::TABLE,
			sqlite_escape_string ($title)
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}

	public function CreateEntry (string $title, string $summary, string $body,
	                             integer  $owner, boolean $status = true,
	                             boolean $onfrontpage = true, 
	                             boolean $allowcomments = true, 
	                             boolean $autodisable = false)
	{
		return SQLitePapyrineEntry::Create ($this->GetID (), $title, $summary, 
	                                        $body, $owner, $status, $onfrontpage,
	                                        $allowcomments, $autodisable);
	}

	public function CreateCategory (string $title)
	{
		return SQLitePapyrineCategory::Create ($this->GetID (), $title);
	}

	public function GetEntry (integer $id)
	{
		return new SQLitePapyrineEntry ($this->GetID (), $id);
	}

	public function GetCategory (integer $id)
	{
		return new SQLitePapyrineCategory ($this->GetID (), $id);
	}

	public function GetComment (integer $id)
	{
		return new SQLitePapyrineComment ($this->GetID (), $id);
	}

	public function GetID ()
	{
		return $this->__get ("id");
	}

	public function GetTitle ()
	{
		return $this->__get ("title");
	}

	public function SetTitle (string $title)
	{
		return $this->__set ("title", $title);
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
