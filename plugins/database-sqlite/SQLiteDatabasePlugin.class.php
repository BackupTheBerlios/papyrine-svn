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
class SQLiteDatabasePlugin extends PapyrineDatabasePlugin
{
	/**
	 * Our prized database connection.
	 *
	 * @var mixed 
	 */
	public $connection;

	function __construct ()
	{
		global $papyrine;

		$this->connection = new SQLiteDatabase ("/var/www/localhost/htdocs/papyrine/data/papyrine.sqlite"); 
		
		$this->_init ();
	}

   	/**
   	 * The class destructor, closes the database connection if open.
   	 */
	function __destruct () 
	{
		$this->connection->close;
	}

	private function _init ()
	{
		$this->Blog_CreateTable ();
		$this->Category_CreateTable ();
	}

	public function GetBlog ($id)
	{
		return new SQLitePapyrineBlog ($id);
	}

	public function GetUser ($id)
	{
		return new SQLitePapyrineUser ($id);
	}

	public function CreateBlog ($title)
	{
		return SQLitePapyrineBlog::Create ($title);
	}

	public function CreateUser ($nickname, $password, $firstname, $lastname,
	                            $email)
	{
		return SQLitePapyrineUser::Create ($nickname, $password, $firstname, 
		                                   $lastname, $email);
	}

	public function Blog_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (        " .
			" id INTEGER PRIMARY KEY, " .
			" title text NOT NULL     " .
			")                        " ,
			SQLitePapyrineBlog::TABLE
		);

		$this->connection->query ($sql);
	}

	public function Category_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (        " .
			" id INTEGER PRIMARY KEY, " .
			" blog int(11) NOT NULL,  " .
			" title text NOT NULL     " .
			")                        " ,
			SQLitePapyrineCategory::TABLE
		);

		$this->connection->query ($sql);
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
