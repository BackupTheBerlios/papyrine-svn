<?php

/**
 * Papyrine is a weblogging system built using PHP5.
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
 * Decribes a Papyrine category.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage SQLiteDatabasePlugin
 */
class SQLiteCategory extends SQLiteAbstraction implements PapyrineCategory
{
	// Table name
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
	public static function create( $title )
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

		return $papyrine->database->connection->unbufferedQuery( $sql );
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
