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
	 * @uses DB_common::query
	 * @uses DB_result::getRow
	 */
	function __get ($var)
	{
		if (!$this->data)
		{
			// Query the database for the desired entry.
			$result = $this->database->query (sprintf (
				" SELECT * FROM %s " .
				" WHERE id = %s    " .
				" LIMIT 1          " ,
				PapyrineComment::table,
				$this->id)
			);

			// Populate the object from the database.
			$this->data = $result->getRow ($result, DB_FETCHMODE_ASSOC);
		}

		return parent::__get ($var);
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
	 * @return boolean
	 * @uses PapyrineComment::table
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function CreateTable (&$database)
	{
		$database->query (sprintf (
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

		$result->free ();

		return !DB::isError ($result);
	}

	/**
	 * Create a new comment.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $entry Unique id of this entry.
	 * @param string $body New comments's body of text.
	 * @param string $owner_name New comments's creator.
	 * @param string $owner_email New comments's creator's email.
	 * @return boolean
	 * @uses PapyrineComment::table
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_common::quoteSmart
	 * @uses DB_result::free
	 */
	public static function Create (&$database, $entry, $body, $owner_name, 
	                               $owner_email)
	{
		// Generate the query and insert into the database.
		$result = $database->query (sprintf (
			"INSERT INTO %s SET " .
			" entry = %s,       " .
			" body = %s,        " .
			" owner_name = %s,  " .
			" owner_email = %s, " .
			" status = %s,      " .
			" created = NOW()   " ,
			PapyrineComment::table,
			$post,
			$database->quoteSmart ($body), 
			$database->quoteSmart ($owner_name), 
			$database->quoteSmart ($owner_email), 
			0
		);

		$result->free ();

		return !DB::isError ($result);
	}

	/**
	 * Delete the entry and decrement the entry comments counter.
	 *
	 * @return boolean
	 * @uses PapyrineEntry::table
	 * @uses PapyrineComment::table
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public function Delete ()
	{
		// Decrement the comments counter for this entry.
		$result = $this->database->query (sprintf (
			" UPDATE %s SET           " .
			" comments = comments - 1 " .
			" WHERE id = %s           " .
			" LIMIT 1                 " ,
			PapyrineEntry::table,
			$this->data["entry"])
		);

		$result->free ();

		// If the previous query worked, actually delete the comment.
		if (!DB::isError ($result))
		{
			$result2 = $this->database->query (sprintf (
				" DELETE FROM %s " .
				" WHERE id = %s  " .
				" LIMIT 1        " ,
				PapyrineComment::table,
				$this->data["id"])
			);

			$result2->free ();

			return !DB::isError ($result2);
		} else
			return false;
	}
}

?>
