<?php

/**
 * SQLiteEntry is a SQLite implementation of the PapyrineEntry class.
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

/**
 * Decribes a Papyrine entry.
 */
class SQLiteEntry extends SQLiteAbstraction implements PapyrineEntry
{
	const TABLE = 'papyrine_entries';

	function __construct ($id)
	{
		$this->_fetchSQL = sprintf (
			" SELECT * FROM %s " .
			" WHERE id = %s    " .
			" LIMIT 1          " ,
			self::TABLE,
			$id
		);

		$this->_deleteSQL = sprintf (
			" DELETE FROM %s " .
			" AND id = %s    " .
			" LIMIT 1        " ,
			self::TABLE,
			$id
		);

	}

	public static function &create ($title, $body, $owner, $status = true)
	{
		global $papyrine;

		// Generate the query and insert into the database.
		$sql = sprintf (
			"INSERT INTO %s                    " .
			" (title, linktitle, body, owner,  " .
			"  status, created)                " .
			" VALUES                           " .
			" ('%s', '%s', '%s', %s, %s, '%s') " ,
			self::TABLE,
			sqlite_escape_string ($title),
			sqlite_escape_string ($title),
			sqlite_escape_string ($body),
			$owner,
			($status ? 1 : 0),
			time()
		);

		$result = $papyrine->database->connection->unbufferedQuery ($sql);

		return new SQliteEntry (
			$papyrine->database->connection->lastInsertRowid()
		);
	}

	public static function createTable ()
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
			self::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function getID ()
	{
		return $this->__get ("id");
	}

	public function getTitle ()
	{
		return $this->__get ("title");
	}

	public function getBody ()
	{
		return $this->__get ("body");
	}

	public function getOwner ()
	{
		return new SQLiteUser ($this->__get ("owner"));
	}

	public function getStatus ()
	{
		return $this->__get ("status");
	}

	public function setTitle ($title)
	{
		return $this->__set ("title", $title);
	}

	public function setBody ($body)
	{
		return $this->__set ("body", $body);
	}

	public function setOwner ($owner)
	{
		return $this->__set ("owner", $owner);
	}

	public function setStatus ($status)
	{
		return $this->__set ("status", $status);
	}
}
/*
	public function CreateComment ($body, $owner_name, $owner_email)
	{			
		$this->data["comments"] += 1;
		$this->mod["comments"]   = true;

		return PapyrineComment::Create (
			$this->database, 
			$this->data["id"],
			$body, 
			$owner_name, 
			$owner_email
		);
	}	public function GetCategories ()
	{
		$result = $this->database->query (
			" SELECT !.category FROM !, ! " .
			" WHERE !.id = !.category     " .
			" AND !.entry = ?             " .
			" ORDER BY !.title ASC        " ,
			array (
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategory::TABLE,
				PapyrineCategory::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategoryRelationship::TABLE,
				$this->data["id"],
				PapyrineCategory::TABLE
			)
		);

		$categories = array ();
		while ($row =& $result->fetchRow ())
		{
			$categories[] = new PapyrineCategory ($this->database, 
			                                      $row ["category"]);
		}

		$result->free ();

		return $categories;
	}

	public function GetNext ()
	{
		$sql = " SELECT id FROM !     " .
		       " WHERE blog = ?       " .
		       " AND status = ?       " .
		       " AND created > ?      " .
		       " ORDER BY created ASC " .
		       " LIMIT 1              " ;

		$params = array (
			self::TABLE,
			$this->data["blog"],
			$this->data["status"],
			$this->data["created"]
		);

		return new PapyrineEntry ($this->database, 
		                          $this->database->getOne ($sql, $params));
	}

	public function GetPrevious ()
	{
		$sql = " SELECT id FROM !      " .
		       " WHERE blog = ?        " .
		       " AND status = ?        " .
		       " AND created < ?       " .
		       " ORDER BY created DESC " .
		       " LIMIT 1               " ;

		$params = array (
			self::TABLE,
			$this->data["blog"],
			$this->data["status"],
			$this->data["created"]
		);

		return new PapyrineEntry ($this->database,
		                          $this->database->getOne ($sql, $params));
	}
*/
?>
