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
 * Handles the basics for object updating.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineObject
{
	/**
	 * Our database connection.
	 *
	 * @var mixed 
	 */
	protected $database;

	/**
	 * Array of information describing this object.
	 *
	 * @var array|boolean
	 */
	protected $data = false;

	/**
	 * Temp value for use when creating database.
	 *
	 * @var mixed
	 */
	protected $id;

	/**
	 * Contains the changes that need to be sync'd with the database.
	 *
	 * @var array 
	 */
	protected $mod;

	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	protected $sqltable;

	/**
	 * Constructor, sets up our table, database and empty array.
	 *
	 * @param string $table Database table to access.
	 * @param mixed $database Reference for already opened database.
	 */
	function __construct (&$database, $table) 
	{
		$this->sqltable = $table
		$this->database = &$database;
		$this->mod = array ();
	}

	/**
	 * Destructor called when this object is done being used. Synchronizes
	 * the object with the database if needed.
	 *
	 * @uses DB_common::query
	 * @uses DB_common::quoteSmart
	 * @uses DB_result::free
	 */
	function __destruct () 
	{
		if (count ($this->mod) > 0)
		{
			$updates = array ();
			foreach ($this->mod as $key=>$val)
			{
				$updates[] = "{$key} = " . 
				             $this->database->quoteSmart ($this->data[$key]);
			}

			$result = $this->database->query (
				" UPDATE ! SET ! " .
				" WHERE id = ?   " .
				" LIMIT 1        " ,
				array (
					$this->sqltable,
					join (", ", $updates),
					$this->data["id"]
				)
			);

			$result->free ();
		}
	}

	/**
	 * If we are tracking the variable, return it.
	 */
	protected function __get ($var) 
	{
		if (isset ($this->data[$var]))
			return $this->data[$var];
	}

	/**
	 * If we are tracking the variable, change it locally and let us know
	 * there are changes waiting to be submitted to the database.
	 */
	protected function __set ($var, $val) 
	{
		if (isset ($this->data[$var]))
		{
			$this->data[$var] = $val;
			$this->mod[$var]  = true;
		}
	}

	/**
	 * Return an array describing this object.
	 *
	 * @return array
	 */
	public function ToArray ()
	{
		return $this->data;
	}
}

?>
