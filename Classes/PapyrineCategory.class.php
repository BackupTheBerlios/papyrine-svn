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
	const table = "papyrine_categories";

	/**
	 * PapyrineCategory constructor.
	 *
	 * @param integer $id Category's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineCategory::table
	 */
	function __construct (&$database, $id) 
	{
		// Initial PapyrineObject.
		parent::_construct ($database, PapyrineCategory::table);

		$this->id = $id;
	}

	/**
	 * Populate the object when we need it.
	 *
	 * @uses PapyrineCategory::table
	 */
	function __get ($var)
	{
		if (!$this->data)
		{
			// Query the database for the desired entry.
			$result = sqlite_query ($this->database, sprintf (
				" SELECT * FROM %s " .
				" WHERE id = %s    " .
				" LIMIT 1          " ,
				PapyrineCategory::table,
				$this->id)
			);

			// Populate the object from the database.
			$this->data = sqlite_fetch_array ($result, SQLITE_ASSOC);
		}

		return parent::_get ($var);
	}

	/**
	 * Get an array of entries for this category.
	 * 
	 * @return array
	 * @uses PapyrineEntry::table
	 * @uses PapyrineCategoryRelationship::table
	 */
	public function GetEntries ($limit = 10)
	{
		$result = sqlite_query ($this->database, sprintf (
			" SELECT %s.id FROM %s, %s " .
			" WHERE %s.category = %s   " .
			" AND %s.id = %s.entry     " .
			" ORDER BY %s.created ASC  " .
			" LIMIT %s                 " ,
			PapyrineEntry::table,
			PapyrineEntry::table,
			PapyrineCategoryRelationship::table,
			PapyrineCategoryRelationship::table,
			$this->data["id"]
			PapyrineEntry::table,
			PapyrineCategoryRelationship::table,
			PapyrineEntry::table,
			$limit)
		);

		$entries = array ();
		while (sqlite_has_more ($result))
		{
			$entries[] = new PapyrineEntry ($this->database, 
			                                sqlite_fetch_single ($result));
		}

		return $entries;
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineCategory::table
	 */
	public static function CreateTable (&$database)
	{
		sqlite_query ($database, sprintf (
			"CREATE TABLE %s (                    " .
			" id int(11) NOT NULL auto_increment, " .
			" blog int(11) NOT NULL,              " .
			" title text NOT NULL,                " .
			" PRIMARY KEY (id),                   " .
			" FULLTEXT KEY body (title)           " .
			") TYPE=MyISAM;                       " ,
			PapyrineCategory::table)
		);
	}

	/**
	 * Create a new category.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $blog Unique id of this blog.
	 * @param string $title New category's title.
	 * @return integer
	 * @uses PapyrineCategory::table
	 */
	public static function Create (&$database, $blog, $title)
	{
		// Generate the query and insert into the database.
		$result = sqlite_query ($database, sprintf (
			"INSERT INTO %s SET " .
			" blog = %s,        " .
			" title = %s        " ,
			PapyrineCategory::table,
			$blog,
			sqlite_escape_string ($title)
		);

		// If everything worked, return the PapyrineCategory object created.
		if ($result)
			return sqlite_last_insert_rowid ($database);
	}

	/**
	 * Delete the category.
	 *
	 * @uses PapyrineCategory::table
	 */
	public function Delete ()
	{
		sqlite_query ($this->database, sprintf (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			PapyrineCategory::table,
			$this->data["id"])
		);
	}
}

?>
