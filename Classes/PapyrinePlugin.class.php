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
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrinePlugin extends PapyrineObject
{
	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	const table = "papyrine_plugins";

	/**
	 * PapyrinePlugin constructor.
	 *
	 * @param integer $id Plugin's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrinePlugin::table
	 */
	function __construct (&$database, $id) 
	{
		// Initial PapyrineObject.
		parent::_construct ($database, PapyrinePlugin::table);

		$this->id = $id;
	}

		//$this->database =& Papyrine::connect ("file");

	/**
	 * Populate the object when we need it.
	 *
	 * @uses PapyrineCategory::table
	 * @uses DB_common::getRow
	 */
	function __get ($var)
	{
		if (!$this->data)
		{
			$this->data = $this->database->getRow (
				" SELECT * FROM %s " .
				" WHERE id = %s    " .
				" LIMIT 1          " ,
				array (
					PapyrinePlugin::table,
					$this->id
				),
				DB_FETCHMODE_ASSOC
			);
		}

		return parent::__get ($var);
	}

	public static function CreateTable (&$database)
	{
		sqlite_query ($database, sprintf (
			"CREATE TABLE %s (                    " .
			" id int(11) NOT NULL auto_increment, " .
			" name text NOT NULL,                 " .
			" description text NOT NULL,          " .
			" version text NOT NULL,              " .
			" class text NOT NULL,                " .
			" modifier int(11) NOT NULL,          " .
			" syndicator int(11) NOT NULL,        " .
			" smarty text NOT NULL,               " .
			" templates text NOT NULL             " .
			") TYPE=MyISAM;                       " ,
			PapyrinePlugin::table)
		);
	}

	public static function Create (&$database, $name, $description, $version,
	                               $smarty = false, $templates = false, 
	                               $modifier = false, $syndicator = false)
	{
		// Generate the query and insert into the database.
		return sqlite_query ($database, sprintf (
			"INSERT INTO %s SET " .
			" name = %s,        " .
			" description = %s, " .
			" version = %s,     " .
			" class = %s,       " .
			" modifier = %s,    " .
			" syndicator = %s,  " .
			" smarty = %s,      " .
			" templates = %s    " .
			PapyrinePlugin::table,
			sqlite_escape_string ($name),
			sqlite_escape_string ($description),
			sqlite_escape_string ($version),
			sqlite_escape_string ($class),
			($modifier   ? 1 : 0),
			($syndicator ? 1 : 0),
			($smarty     ? sqlite_escape_string ($smarty)    : ""),
			($templates  ? sqlite_escape_string ($templates) : ""),
			($modifiers  ? sqlite_escape_string ($modifiers) : "")
		);
	}

	/**
	 * Delete the entry and decrement the entry comments counter.
	 *
	 * @return boolean
	 * @uses PapyrinePlugin::table
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public function Delete ()
	{
		$result = $this->database->query (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			array (
				PapyrinePlugin::table,
				$this->data["entry"]
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}
}

?>
