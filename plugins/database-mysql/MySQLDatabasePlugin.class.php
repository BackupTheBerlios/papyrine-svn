<?php

/**
 * MySQLDatabasePlugin adds a the MySQL database to Papyrine.
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
class SQLiteDatabasePlugin extends PapyrinePlugin
{
	const TABLES = array (
		"entries" => "papyrine_entries"
	);

	/**
	 * Our prized database connection.
	 *
	 * @var mixed 
	 */
	private $database = false;

	function __construct ()
	{
		global $papyrine;
/*
		$papyrine->RegisterHook ("entry_initialize", 
		                         "Initialize",
		                         $this);

		$papyrine->RegisterHook ("entry_populate_data", 
		                         "EntryPopulateData",
		                         $this);

		$papyrine->RegisterHook ("entry_delete", 
		                         "EntryDelete",
		                         $this);
*/
	}

   	/**
   	 * The class destructor, closes the database connection if open.
   	 */
	function __destruct () 
	{
		$this->database->disconnect();
	}

   	/**
   	 * If we ask for the database and we don't have it, create one.
   	 */
	function __get ($var) 
	{
		if ($var == "database")
		{
			if (!$this->database)
			{
				$dsn = array(
    				"phptype"  => "sqlite",
				    "hostspec" => "/" . $_SERVER["DOCUMENT_ROOT"] . "data/" . 
					              "papyrine.db"
				);

				return DB::connect ($dsn);
			} else
				return $this->database;
		}
	}

	public function Initialize (&$params, &$output)
	{
		// check tables, create if needed.
	}

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
	}*/
}

?>
