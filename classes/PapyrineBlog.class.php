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
 * Decribes a Papyrine blog.
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
	const TABLE = "papyrine_blogs";

	/**
	 * PapyrineCategory constructor.
	 *
	 * @param integer $id Blog's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineBlog::TABLE
	 */
	function __construct (&$database, $id) 
	{
		// Initialize PapyrineObject.
		parent::_construct ($database, self::TABLE);

		// How to populate data.
		$this->sql = sprintf (
			PapyrineQueries::BLOG_POPULATE_DATA,
			self::TABLE,
			$id
		);
	}

	public function GetEntry ($id)
	{
		return PapyrineEntry ($this->blog, $id);
	}

	/**
	 * Create a new category.
	 *
	 * @param string $title The category's title.
	 * @return integer
	 */
	public function CreateCategory ($title)
	{
		return PapyrineCategory::Create (
			$this->database, 
			$this->data["id"],
			$title
		);
	}

	/**
	 * Get a category by id.
	 *
	 * @param integer $id Category's unique id.
	 * @return PapyrineCategory
	 */
	public function GetCategory ($id)
	{
		return new PapyrineCategory ($this->database, $this->blog, $id);
	}

	/**
	 * Create a new category for this blog.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param string $title New category's title.
	 * @return integer
	 * @uses PapyrineCategory::Create
	 */
	public static function CreateCategory (&$database, $title)
	{
		return PapyrineCategory::Create (
			$database, 
			$this->id,
			$title
		);
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for an already opened database.
	 * @uses PapyrineBlog::TABLE
	 */
	public static function CreateTable (&$database)
	{
		// Query the database.
		$result = $database->query (
			"CREATE TABLE ! (                     " .
			" id int(11) NOT NULL auto_increment, " .
			" title text NOT NULL,                " .
			" PRIMARY KEY (id)                    " .
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
	 * Create a new blog.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param string $title New blog's title.
	 * @return integer
	 * @uses PapyrinBlog::TABLE
	 */
	public static function Create (&$database, $title)
	{
		// Generate the query and insert into the database.
		$result = $database->query (
			"INSERT INTO ! SET " .
			" title = ?        " ,
			array (
				self::TABLE,
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
	 * @uses PapyrineBlog::TABLE
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
