<?php

/**
 * SQLiteDatabasePlugin is a SQLite implementation of the PapyrineDatabase class.
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
 * @subpackage SQLiteDatabasePlugin
 * @author Thomas Reynolds <tdreyno@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

require_once 'SQLiteAbstraction.php';
require_once 'SQLiteCategory.php';
require_once 'SQLiteComment.php';
require_once 'SQLiteEntry.php';
require_once 'SQLiteUser.php';

/**
 * Defines this database.
 */
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
		SQLiteCategory::createTable ();
		SQLiteComment::createTable ();
		SQLiteEntry::createTable ();
		SQLiteUser::createTable ();
	}

	public function emailExists ($email)
	{
		return SQLiteUser::emailExists ($email);
	}

	public function getUser ($id)
	{
		return new SQLiteUser ($id);
	}

	public function getUsers ($as_array = false)
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

	public function &createUser ($email, $name, $password)
	{
		return SQLiteUser::create ($email, $name, $password);
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

	public function &createEntry ($title, $body, $owner, $status = true)
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

	public function &createCategory ($title)
	{
		return SQLiteCategory::create ($title);
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
