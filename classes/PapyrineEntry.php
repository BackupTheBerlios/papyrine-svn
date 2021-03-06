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
 * A general interface for accessing a Papyrine entry.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
interface PapyrineEntry
{
	// General functions.
	public function __construct ($id);

	// Functions to fetch information.
	public function getID ();
	public function getTitle ();
	public function getBody ();
	public function getOwner ();
	public function getStatus ();
	//abstract public function GetCategories ();
	//abstract public function GetComments ();
	//abstract public function GetNext ();
	//abstract public function GetPrevious ();
	//abstract public function AddCategory (integer $id);
	//abstract public function RemoveCategory (integer $id);

	// Functions to set information.
	public function setTitle ($title);
	public function setBody ($body);
	public function setOwner ($owner);
	public function setStatus ($status);

	// Functions to create.
	public static function &create ($title, $body, $owner, $status = true);

	//abstract public function CreateComment (string $body, string $owner_name,
	//                                        string $owner_email)

	// Functions to delete.
	public function delete ();
}

?>
