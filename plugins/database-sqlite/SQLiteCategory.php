<?php

/**
 * SQLiteCategory is a SQLite implementation of the PapyrineCategory class.
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
 * Decribes a Papyrine category.
 */
class SQLiteCategory extends SQLiteAbstraction implements PapyrineCategory
{
	const TABLE = 'papyrine_categories';

	function __construct( $id )
	{
		$this->_fetchSQL = sprintf(
			" SELECT id, title " .
			" FROM %s WHERE    " .
			" id = %s          " .
			" LIMIT 1          " ,
			self::TABLE,
			$id
		);

		$this->_deleteSQL = sprintf(
			" DELETE FROM %s " .
			" WHERE id = %s  " .
			" LIMIT 1        " ,
			self::TABLE,
			$id
		);
	}

	/**
	 * Create a new blog.
	 *
	 * @param integer $blog New category's blog.
	 * @param string $title New category's title.
	 * @return integer
	 */
	public static function &create( $title )
	{
		global $papyrine;

		$sql = sprintf (
			"INSERT INTO %s " .
			" (title)       " .
			"VALUES         " .
			" ('%s')        " ,
			self::TABLE,
			sqlite_escape_string( $title )
		);

		$result = $papyrine->database->connection->unbufferedQuery ($sql);

		return new SQliteCategory (
			$papyrine->database->connection->lastInsertRowid()
		);
	}

	public static function createTable ()
	{
		$sql = sprintf (
			"CREATE TABLE %s (        " .
			" id INTEGER PRIMARY KEY, " .
			" title text NOT NULL     " .
			")                        " ,
			self::TABLE
		);

		$this->connection->unbufferedQuery ($sql);
	}

	public function getID()
	{
		return $this->__get( "id" );
	}

	public function getTitle ()
	{
		return $this->__get( "title" );
	}

	public function getEntries( $limit = false )
	{
	}

	public function setTitle( $title )
	{
		return $this->__set( "title", $title );
	}
}

?>
