<?php

/**
 * SQLiteAbstraction contains functions used by all classes in this plugin.
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
 * Provides the functionality required by PapyrineBlog using a SQLite database.
 */
class SQLiteAbstraction
{
	// Fetch SQL
	protected $_fetchSQL;

	// Delete SQL
	protected $_deleteSQL;

	protected $_data;

	private function _populate ()
	{
		global $papyrine;

		$result = $papyrine->database->connection->unbufferedQuery ($this->_fetchSQL);
		$this->_data =& $result->fetch(SQLITE_ASSOC);
	}

	function __get ( $var )
	{
		if ($this->_data == false)
			$this->_populate();

		if (isset( $this->_data[$var] ))
			return $this->_data [$var];
	}

	function __set ($var, $val)
	{
		//
	}

	/**
	 * Delete the blog.
	 */
	public function delete()
	{
		global $papyrine;

		return $papyrine->database->connection->unbufferedQuery(
			$this->_deleteSQL);
	}
}
