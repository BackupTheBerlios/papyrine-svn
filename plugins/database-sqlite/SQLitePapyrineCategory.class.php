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
 * Decribes a Papyrine category.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage SQLiteDatabasePlugin
 */
class SQLitePapyrineCategory implements PapyrineCategory
{
	const TABLE = 'papyrine_categories';
	private $_id;
	private $_data = false;

	function __construct (integer $blog, string $id)
	{
		$this->_id = array (
			"blog" => $blog,
			"id"   => $id
		);
	}

	private function _Populate ()
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT           " .
			"  id, title, blog " .
			" FROM %s WHERE    " .
			"  blog = %s AND   " .
			"  id = %s         " .
			" LIMIT 1          " ,
			self::TABLE,
			$this->_id ["blog"],
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
	 * Create a new blog.
	 *
	 * @param integer $blog New category's blog.
	 * @param string $title New category's title.
	 * @return integer
	 */
	public static function Create (integer $blog, string $title)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s " .
			" (blog, title) " .
			"VALUES         " .
			" (%s, '%s')    " ,
			self::TABLE,
			$blog,
			sqlite_escape_string ($title)
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}

	public function GetID ()
	{
		return $this->__get ("id");
	}

	public function GetTitle ()
	{
		return $this->__get ("title");
	}

	public function GetBlog ()
	{
		return new SQLitePapyrineBlog ($this->__get ("blog"));
	}

	public function GetEntries (integer $limit = false)
	{
		/*$result = $this->database->query (
			PapyrineQueries::CATEGORY_GET_ENTRIES,
			array (
				PapyrineEntry::TABLE,
				PapyrineEntry::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategoryRelationship::TABLE,
				$this->data["id"]
				PapyrineEntry::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineEntry::TABLE,
				($limit ? "LIMIT {$limit}" : "")
			)
		);

	const CATEGORY_GET_ENTRIES = 
		" SELECT !.id FROM !, !               " .
		" WHERE !.category = ?                " .
		" AND !.id = !.entry                  " .
		" ORDER BY !.created ASC              " .
		" !                                   " ;
*/
	}

	public function SetTitle (string $title)
	{
		return $this->__set ("title", $title);
	}

	public function SetBlog (integer $blog)
	{
		return $this->__set ("title", $blog);
	}

	/**
	 * Delete the category.
	 */
	public function Delete ()
	{
		global $papyrine;

		$sql = sprintf (
			" DELETE FROM %s  " .
			" WHERE blog = %s " .
			" AND id = %s     " .
			" LIMIT 1         " ,
			self::TABLE,
			$this->_id ["blog"],
			$this->_id ["id"]
		);

		return $papyrine->database->connection->unbufferedQuery ($sql);
	}
}

?>
