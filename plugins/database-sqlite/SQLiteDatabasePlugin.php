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

require_once 'SQLiteAbstraction.php';
require_once 'SQLiteCategory.php';
require_once 'SQLiteComment.php';
require_once 'SQLiteEntry.php';
require_once 'SQLiteUser.php';

class SQLiteDatabasePlugin implements PapyrineDatabase
{
	/**
	 * Our prized database connection.
	 *
	 * @var mixed 
	 */
	public $connection;

	function __construct()
	{
		$this->connection =& new SQLiteDatabase (
			BASE . "data/papyrine.sqlite" 
		);
	}

	public static function install()
	{
		// If first run
		$this->Category_CreateTable ();
		$this->Comment_CreateTable ();
		$this->Entry_CreateTable ();
		$this->User_CreateTable ();
	}

	public function emailExists ($email)
	{
		return SQLiteUser::emailExists ($email);
	}

	public function getUser( $id )
	{
		return new SQLiteUser( $id );
	}

	public function getUsers( $as_array = false )
	{
		$sql = sprintf (
			" SELECT * FROM %s",
			SQLiteUser::TABLE
		);

		$result = $this->connection->unbufferedQuery ($sql);

		$output = array ();

		foreach ($result as $row) {
			$output [] = new SQLiteUser ($row ["id"]);
		}

		return $output;
	}

	public function createUser( $email, $name, $password )
	{
		return SQLiteUser::create( $email, $name, $password );
	}

	public function getEntry ($id)
	{
		return new SQLiteEntry ($id);
	}

	public function getEntries ()
	{
		$sql = sprintf (
			" SELECT * FROM %s",
			SQLiteEntry::TABLE
		);

		$result = $this->connection->unbufferedQuery ($sql);

		$output = array ();

		foreach ($result as $row) {
			$output [] = new SQLiteEntry ($row ["id"]);
		}

		return $output;
	}

	public function createEntry ($title, $body, $owner, $status = true)
	{
		return SQLiteEntry::create ($title, $body, $owner, $status = true);
	}

	public function getCategory ($id)
	{
		return new SQLiteCategory ($id);
	}

	public function getCategories ()
	{
		$sql = sprintf (
			" SELECT * FROM %s",
			SQLiteCategory::TABLE
		);

		$result = $this->connection->unbufferedQuery ($sql);

		$output = array ();

		foreach ($result as $row) {
			$output [] = new SQLiteCategory ($row ["id"]);
		}

		return $output;
	}

	public function createCategory ($title)
	{
		return SQLiteCategory::create ($title);
	}

	public function Category_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (        " .
			" id INTEGER PRIMARY KEY, " .
			" title text NOT NULL     " .
			")                        " ,
			SQLiteCategory::TABLE
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
			SQLiteComment::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function Entry_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (                      " .
			" id INTEGER PRIMARY KEY,               " .
			" title text NOT NULL,                  " .
			" linktitle text NOT NULL,              " .
			" body text NOT NULL,                   " .
			" created timestamp(14) NOT NULL,       " .
			" status INTEGER NOT NULL,              " .
			" owner INTEGER NOT NULL,               " .
			" comments INTEGER NOT NULL default '0' " .
			")                                      " ,
			SQLiteEntry::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function User_CreateTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (         " .
			" id INTEGER PRIMARY KEY,  " .
			" email text NOT NULL,     " .
			" password text NOT NULL,  " .
			" name text NOT NULL       " .
			")                         " ,
			SQLiteUser::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function import( $file )
	{
		// Takes an RSS (RDF?) file and imports it
	}

	public function export()
	{
		// Export the current data as RSS (RDF?)
	}
}

?>
