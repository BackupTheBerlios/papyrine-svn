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
interface PapyrineComment
{
	// General functions.
	public function __construct( $id );

	// Functions to fetch information.
	public function getID();
	public function getEntry();
	public function getBody();
	public function getOwnerName();
	public function getOwnerEmail();
	public function getStatus();
	public function getCreated();

	// Functions to set information.
	public function setBody( $body );
	public function setOwnerName( $name );
	public function setOwnerEmail( $email );
	public function setStatus( $status );

	// Functions to create.
	public static function &create( $entry, $body, $owner_name, $owner_email );

	// Functions to delete.
	public function delete();
}

?>
