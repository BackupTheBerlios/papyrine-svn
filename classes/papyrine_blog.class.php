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
	 * Create a new category.
	 *
	 * @param string $title The category's title.
	 * @return integer
	 */
	public function CreateCategory ($title)
	{
		return PapyrineCategory::Create (
			$this->database, 
			$this->data["id"],
			$title
		);
	}

	/**
	 * Get a category by id.
	 *
	 * @param integer $id Category's unique id.
	 * @return PapyrineCategory
	 */
	public function GetCategory ($id)
	{
		return new PapyrineCategory ($this->database, $this->id, $id);
	}

	/**
	 * Create a new category for this blog.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param string $title New category's title.
	 * @return integer
	 * @uses PapyrineCategory::Create
	 */
	public static function CreateCategory (&$database, $title)
	{
		return PapyrineCategory::Create (
			$database, 
			$this->id,
			$title
		);
	}
}

?>
