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
 * Decribes a Papyrine comment.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineComment extends PapyrineObject
{
	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	const table = "papyrine_comments";

	/**
	 * PapyrineComment constructor.
	 *
	 * @param integer $id Comment's unique id.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineComment::table
	 */
	function __construct (&$database, $id) 
	{
		// Initial PapyrineObject.
		parent::_construct ($database, PapyrineComment::table);

		$this->id = $id;
	}

	/**
	 * Populate the object when we need it.
	 *
	 * @uses PapyrineComment::table
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
				PapyrineComment::table,
				$this->id)
			);

			// Populate the object from the database.
			$this->data = sqlite_fetch_array ($result, SQLITE_ASSOC);
		}

		return parent::_get ($var);
	}

	/**
	 * Get the entry as an object.
	 *
	 * @return PapyrineEntry
	 */
	public function GetEntry ()
	{
		return new PapyrineEntry ($this->database, $this->data["entry"]);
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineComment::table
	 */
	public static function CreateTable (&$database)
	{
		sqlite_query ($database, sprintf (
			"CREATE TABLE %s (                    " .
			" id int(11) NOT NULL auto_increment, " .
			" entry int(11) NOT NULL,             " .
			" body text NOT NULL,                 " .
			" created timestamp(14) NOT NULL,     " .
			" status int(11) NOT NULL,            " .
			" owner_name text NOT NULL,           " .
			" owner_email text NOT NULL,          " .
			" PRIMARY KEY (id),                   " .
			" FULLTEXT KEY body (body)            " .
			") TYPE=MyISAM;                       " ,
			PapyrineComment::table)
		);
	}

	/**
	 * Create a new comment.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $entry Unique id of this entry.
	 * @param string $body New comments's body of text.
	 * @param string $owner_name New comments's creator.
	 * @param string $owner_email New comments's creator's email.
	 * @return interger
	 * @uses PapyrineComment::table
	 */
	public static function Create (&$database, $entry, $body, $owner_name, 
	                               $owner_email)
	{
		// Generate the query and insert into the database.
		$result = sqlite_query ($database, sprintf (
			"INSERT INTO %s SET " .
			" entry = %s,       " .
			" body = %s,        " .
			" owner_name = %s,  " .
			" owner_email = %s, " .
			" status = %s,      " .
			" created = NOW()   " ,
			PapyrineComment::table,
			$post,
			sqlite_escape_string ($body), 
			sqlite_escape_string ($owner_name), 
			sqlite_escape_string ($owner_email), 
			0
		);

		// If everything worked, return the PapyrineComment object created.
		if ($result)
			return sqlite_last_insert_rowid ($database);
	}

	/**
	 * Delete the entry and decrement the entry comments counter.
	 *
	 * @uses PapyrineEntry::table
	 * @uses PapyrineComment::table
	 */
	public function Delete ()
	{
		sqlite_query ($this->database, sprintf (
			" UPDATE %s SET           " .
			" comments = comments - 1 " .
			" WHERE id = %s           " .
			" LIMIT 1                 " ,
			PapyrineEntry::table,
			$this->data["entry"])
		);

		sqlite_query ($this->database, sprintf (
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			PapyrineComment::table,
			$this->data["id"])
		);
	}
}

?>
