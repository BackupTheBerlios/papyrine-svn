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
 * Decribes a Papyrine user.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
interface PapyrineUser
{
	// General functions.
	public function __construct (integer $id);

	// Functions to fetch information.
	public function GetID ();
	public function GetNickname ();
	public function GetPassword ();
	public function GetFirstName ();
	public function GetLastName ();
	public function GetEmail ();

	// Functions to set information.
	public function SetNickname (string $nickname);
	public function SetPassword (string $password);
	public function SetFirstName (string $firstname);
	public function SetLastName (string $lastname);
	public function SetEmail (string $email);

	// Functions to create.
	public static function Create ($nickname,
	                               $password, $firstname,
	                               $lastname, $email);

	// Functions to delete.
	public function Delete ();
}

?>
