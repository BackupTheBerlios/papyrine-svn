<?php

/**
 * SQLiteDatabasePlugin adds a the SQLite database to Papyrine.
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
 * @subpackage Plugins
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Plugins
 */
class SQLiteDatabasePlugin implements PapyrineDatabasePlugin
{
	const BLOG_TABLE = 'papyrine_blogs';

	/**
	 * Our prized database connection.
	 *
	 * @var mixed 
	 */
	private $_database;

	function __construct ()
	{
		global $papyrine;

		$this->_database = sqlite_open ("/var/www/localhost/htdocs/papyrine/data/papyrine.db");
		
		$this->_init ();
	}

   	/**
   	 * The class destructor, closes the database connection if open.
   	 */
	function __destruct () 
	{
		sqlite_close ($this->_database);
	}

	private function _init ()
	{
		$this->Blog_CreateTable ();
	}

	public function Blog_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (     " .
			" title text NOT NULL, " .
			" PRIMARY KEY (title)  " .
			")                     " ,
			self::BLOG_TABLE
		);

		sqlite_query ($this->_database, $sql);
	}

	public function Blog_Create ($title)
	{
		$sql = sprintf (
			"INSERT INTO %s " .
			" (title)       " .
			"VALUES         " .
			" ('%s')        " ,
			self::BLOG_TABLE,
			sqlite_escape_string ($title)
		);

		return sqlite_query ($this->_database, $sql);
	}

	public function Blog_Delete ($id)
	{
		$sql = sprintf (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			self::BLOG_TABLE,
			$id
		);

		return sqlite_query ($this->_database, $sql);
	}

/*
	public function EntryPopulateData ($params, &$output)
	{
		$output = $this->database->getRow (
			" SELECT * FROM ! " .
			" WHERE blog = ?  " .
			" AND ! = ?       " .
			" LIMIT 1         " ,
			array (
				self::TABLES ["entries"],
				$params ["blog"],
				(is_numeric ($params ["id"]) ? "id" : "linktitle"),
				$id
			),
			DB_FETCHMODE_ASSOC
		);
	}

	public function EntryDelete ($params, &$output)
	{
		$result = $this->database->query (
			" DELETE FROM ! " .
			" WHERE id = ?  " .
			" LIMIT 1       " ,
			array (
				self::TABLES ["entries"],
				$this->params ["id"]
			)
		);

		$result->free ();

		$output = !DB::isError ($result);
	}
*/
}

?>
