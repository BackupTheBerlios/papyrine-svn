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
 * Decribes the relationship between an entry and a category.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineCategoryRelationship extends PapyrineObject
{
	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	const TABLE = "papyrine_comment_relationships";

	/**
	 * PapyrineCategoryRelationship constructor.
	 *
	 * @param integer $entry Entry's unique id.
	 * @param integer $comment Entry's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineCategoryRelationship::TABLE
	 */
	function __construct (&$database, $entry, $category) 
	{
		// Initial PapyrineObject.
		parent::_construct ($database, self::TABLE);

		$this->id = array (
			"entry"    => $entry,
			"category" => $category
		);
	}

	/**
	 * Populate the object when we need it.
	 *
	 * @uses PapyrineCategoryRelationship::table
	 * @uses DB_common::getRow
	 */
	function __get ($var)
	{
		if (!$this->data)
		{
			$this->data = $this->database->getRow (
				" SELECT * FROM !  " .
				" WHERE entry = ?  " .
				" AND category = ? " .
				" LIMIT 1          " ,
				array (
					self::TABLE,
					$this->id["entry"],
					$this->id["category"]
				),
				DB_FETCHMODE_ASSOC
			);
		}

		return parent::__get ($var);
	}

	/**
	 * Return the entry as an object.
	 *
	 * @return PapyrineEntry
	 */
	public function GetEntry ()
	{
		return new PapyrineEntry ($this->database, $this->data["entry"]);
	}

	/**
	 * Return the category as an object.
	 *
	 * @return PapyrineCategory
	 */
	public function GetCategory ()
	{
		return new PapyrineCategory ($this->database, $this->data["category"]);
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @return boolean
	 * @uses PapyrineCategoryRelationship::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function CreateTable (&$database)
	{
		$result = $database->query (
			"CREATE TABLE ! (           " .
			" entry int(11) NOT NULL,   " .
			" category int(11) NOT NULL " .
			") TYPE=MyISAM;             " ,
			array (
				self::TABLE
			)
		);

		$result->free ()

		return !DB::isError ($result);
	}

	/**
	 * Create a new relationship.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $entry Unique id of the entry to relate to.
	 * @param integer $category Unique id of the category to relate to.
	 * @return boolean
	 * @uses PapyrineCategoryRelationship::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function Create (&$database, $entry, $category)
	{
		// Generate the query and insert into the database.
		$result = $database->query (
			"INSERT INTO ! SET " .
			" entry = ?,       " .
			" category = ?     " ,
			array (
				self::TABLE,
				$entry,
				$category
			)
		);

		$result->free ()

		return !DB::isError ($result);
	}

	/**
	 * Delete the relationship.
	 *
	 * @return boolean
	 * @uses PapyrineCategoryRelationship::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public function Delete ()
	{
		$result = $this->database->query (
			" DELETE FROM !    " .
			" WHERE entry = ?  " .
			" AND category = ? " .
			" LIMIT 1          " ,
			array (
				self::TABLE,
				$this->data["entry"],
				$this->data["category"]
			)
		);

		$result->free ()

		return !DB::isError ($result);
	}
}

?>
