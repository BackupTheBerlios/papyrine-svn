<?php

/**
 * SQLiteComment is a SQLite implementation of the PapyrineComment class.
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
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Decribes a Papyrine comment.
 */
class SQLiteComment extends SQLiteAbstraction implements PapyrineComment
{
	const TABLE = 'papyrine_comments';

	function __construct( $id )
	{
		$this->_fetchSQL = sprintf (
			" SELECT                                                     " .
			"  id, entry, body, owner_name, owner_email, status, created " .
			" FROM %s WHERE                                              " .
			"  id = %s                                                   " .
			" LIMIT 1                                                    " ,
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

	/**
	 * Create a new comment.
	 */
	public static function &create ($entry, $body, $owner_name, $owner_email)
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s                                           " .
			" (entry, body, owner_name, owner_email, status, created) " .
			"VALUES                                                   " .
			" (%s, '%s', '%s', '%s', %s, NOW())                       " ,
			self::TABLE,
			$entry,
			sqlite_escape_string( $body ),
			sqlite_escape_string( $owner_name ),
			sqlite_escape_string( $owner_email ),
			0
		);

		$result = $papyrine->database->connection->unbufferedQuery ($sql);

		return new SQliteComment (
			$papyrine->database->connection->lastInsertRowid()
		);
	}

	public static function createTable ()
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
			self::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function getID()
	{
		return $this->__get( "id" );
	}

	public function getBody()
	{
		return $this->__get( "body" );
	}

	public function getOwnerName()
	{
		return $this->__get( "owner_name" );
	}

	public function getOwnerEmail ()
	{
		return $this->__get( "owner_email" );
	}

	public function getStatus ()
	{
		return $this->__get( "status" );
	}

	public function getCreated ()
	{
		return $this->__get( "created" );
	}

	public function getEntry ()
	{
		return new SQLiteEntry( $this->__get( "entry" ) );
	}

	public function setBody( $body )
	{
		return $this->__set( "body", $body );
	}

	public function setOwnerName( $name )
	{
		return $this->__set( "owner_name", $name );
	}

	public function setOwnerEmail( $email )
	{
		return $this->__set( "owner_email", $email );
	}

	public function setStatus( $status )
	{
		return $this->__set( "status", $status );
	}

	/**
	 * Delete the category.
	 */
	public function delete ()
	{
		global $papyrine;

		$sql = sprintf (
			" UPDATE %s SET           " .
			" comments = comments - 1 " .
			" WHERE id = %s           " .
			" LIMIT 1                 " ,
			SQLiteEntry::TABLE,
			$this->getID()
		);

		$papyrine->database->connection->unbufferedQuery ($sql);

		parent::delete();
	}
}

?>
