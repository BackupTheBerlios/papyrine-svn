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
 * @subpackage SQLiteDatabasePlugin
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
		
		$this->Blog_CreateTable ();
		$this->Category_CreateTable ();
		$this->Comment_CreateTable ();
		$this->Entry_CreateTable ();
	}

   	/**
   	 * The class destructor, closes the database connection if open.
   	 */
	function __destruct () 
	{
		$this->connection->close ();
	}

	public function GetBlog (integer $id)
	{
		return new SQLitePapyrineBlog ($id);
	}

	public function GetUser (integer $id)
	{
		return new SQLitePapyrineUser ($id);
	}

	public function CreateBlog (string $title)
	{
		return SQLitePapyrineBlog::Create ($title);
	}

	public function CreateUser (string $nickname, string $password, 
	                            string $firstname, string $lastname,
	                            string $email)
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

		$this->connection->unbufferedQuery ($sql);
	}

	public function Category_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (        " .
			" id INTEGER PRIMARY KEY, " .
			" blog INTEGER NOT NULL,  " .
			" title text NOT NULL     " .
			")                        " ,
			SQLitePapyrineCategory::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function Comment_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (          " .
			" id INTEGER PRIMARY KEY,   " .
			" entry INTEGER NOT NULL,   " .
			" body text NOT NULL,       " .
			" created text NOT NULL,    " .
			" status INTEGER NOT NULL,  " .
			" owner_name text NOT NULL, " .
			" owner_email text NOT NULL " .
			")                          " ,
			SQLitePapyrineComment::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function Entry_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (                       " .
			" id INTEGER PRIMARY KEY,                " .
			" blog INTEGER NOT NULL,                 " .
			" title text NOT NULL,                   " .
			" linktitle text NOT NULL,               " .
			" summary text NOT NULL,                 " .
			" body text NOT NULL,                    " .
			" created timestamp(14) NOT NULL,        " .
			" modified timestamp(14) NOT NULL,       " .
			" status INTEGER NOT NULL,               " .
			" owner INTEGER NOT NULL,                " .
			" onfrontpage INTEGER NOT NULL,          " .
			" allowcomments INTEGER NOT NULL,        " .
			" autodisable timestamp(14) NOT NULL,    " .
			" comments INTEGER NOT NULL default '0', " .
			")                                       " ,
			SQLitePapyrineEntry::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
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
