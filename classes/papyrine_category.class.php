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
 * @subpackage Classes
 */
class PapyrineCategory extends PapyrineObject
{
	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	const TABLE = "papyrine_categories";

	/**
	 * PapyrineCategory constructor.
	 *
	 * @param integer $id Category's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineCategory::TABLE
	 */
	function __construct (&$database, $blog, $id) 
	{
		// Initialize PapyrineObject.
		parent::_construct ($database, self::TABLE);

		// Set our unique id for when we actually need to fetch from the table.
		$this->id = array
			"blog" => $blog,
			"id"   => $id
		);
	}

	/**
	 * Populate the object's data store when needed.
	 *
	 * @uses PapyrineCategory::TABLE
	 * @uses PapyrineObject::__get
	 * @uses DB_common::getRow
	 */
	function __get ($var)
	{
		// If the data has not yet been fetched.
		if (!$this->data)
		{
			// Query the database.
			$this->data = $this->database->getRow (
				" SELECT * FROM ! " .
				" WHERE blog = ?  " .
				" AND id = ?      " .
				" LIMIT 1         " ,
				array (
					self::TABLE,
					$this->id ["blog"],
					$this->id ["id"]
				),
				DB_FETCHMODE_ASSOC
			);
		}

		// Use the PapyrineObject's function to return the needed value.
		return parent::__get ($var);
	}

	/**
	 * Get an array of entries associated with this category.
	 * 
	 * @param integer $limit Maximum entries to be returned.
	 * @return array
	 * @uses PapyrineEntry::TABLE
	 * @uses PapyrineCategoryRelationship::TABLE
	 */
	public function GetEntries ($limit = false)
	{
		// Query the database.
		$result = $this->database->query (
			" SELECT !.id FROM !, !  " .
			" WHERE !.category = ?   " .
			" AND !.id = !.entry     " .
			" ORDER BY !.created ASC " .
			" !                      " ,
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

		// Initialize an empty array.
		$entries = array ();

		// For each entry, add an object for it to the output array.
		while ($row =& $result->fetchRow ()) 
			$entries [] = new PapyrineEntry ($this->database, $row ["id"]);

		// Free the result now that we don't need it.
		$result->free ();

		// Return our output array.
		return $entries;
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for an already opened database.
	 * @uses PapyrineCategory::TABLE
	 */
	public static function CreateTable (&$database)
	{
		// Query the database.
		$result = $database->query (
			"CREATE TABLE ! (                     " .
			" id int(11) NOT NULL auto_increment, " .
			" blog int(11) NOT NULL,              " .
			" title text NOT NULL,                " .
			" PRIMARY KEY (id),                   " .
			" FULLTEXT KEY body (title)           " .
			") TYPE=MyISAM;                       " ,
			array (
				self::TABLE
			)
		);

		// Free the unneeded result.
		$result->free ();

		// Return true/false depending on the success of the query.
		return !DB::isError ($result);
	}

	/**
	 * Create a new category.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $blog Unique id of this blog.
	 * @param string $title New category's title.
	 * @return integer
	 * @uses PapyrineCategory::TABLE
	 */
	public static function Create (&$database, $blog, $title)
	{
		// Generate the query and insert into the database.
		$result = $database->query (
			"INSERT INTO ! SET " .
			" blog = ?,        " .
			" title = ?        " ,
			array (
				self::TABLE,
				$blog,
				$title
			)
		);

		// Free the unneeded result.
		$result->free ();

		// Return true/false depending on the success of the query.
		return !DB::isError ($result);
	}

	/**
	 * Delete the category.
	 *
	 * @uses PapyrineCategory::TABLE
	 */
	public function Delete ()
	{
		// Query the database.
		$result = $this->database->query (
			" DELETE FROM ! " .
			" WHERE id = ?  " .
			" LIMIT 1       " ,
			array (
				self::TABLE,
				$this->data["id"]
			)
		);

		// Free the unneeded result.
		$result->free ();

		// Return true/false depending on the success of the query.
		return !DB::isError ($result);
	}
}

?>
