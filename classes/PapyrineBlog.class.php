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
 * Decribes a Papyrine blog and must be extended by a database implementation
 * plugin.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
abstract class PapyrineBlog
{
	// General functions.
	abstract public function __construct ($id);

	// Functions to fetch information.
	abstract public function GetID ();
	abstract public function GetTitle (););
	abstract public function GetEntry ($id);
	abstract public function GetCategory ($id);
	abstract public function GetComment ($id);
	abstract public function GetEntries ();

	// Functions to set information.
	abstract public function SetTitle ($title);

	// Functions to create.
	abstract public static function Create ($title);
	abstract public function CreateEntry ($title, $summary, $body, $owner, 
	                                      $status = true, $onfrontpage = true, 
	                                      $allowcomments = true, 
	                                      $autodisable = false);
	abstract public function CreateCategory ($title);

	// Functions to delete.
	abstract public function Delete ();
}

?>
