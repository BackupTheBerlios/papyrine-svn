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
abstract class PapyrineComment
{
	// General functions.
	abstract public function __construct (integer $id);

	// Functions to fetch information.
	abstract public function GetID ();
	abstract public function GetEntry ();
	abstract public function GetBody ();
	abstract public function GetOwnerName ();
	abstract public function GetOwnerEmail ();
	abstract public function GetStatus ();
	abstract public function GetCreated ();

	// Functions to set information.
	abstract public function SetBody (string $body);
	abstract public function SetOwnerName (string $name);
	abstract public function SetOwnerEmail (string $email);
	abstract public function SetStatus (integer $status);

	// Functions to create.
	abstract public static function Create (integer $entry, string $body, 
	                                        string $owner_name, 
	                                        string $owner_email);

	// Functions to delete.
	abstract public function Delete ();
}

?>
