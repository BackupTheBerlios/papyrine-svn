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
 * Decribes a Papyrine category.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class MySQL extends PapyrinePlugin
{
	function __construct ()
	{
		parent::__construct ("about.xml");

		Papyrine::RegisterHook ("on_query_fetch", 
		                        "SQLQueries::GetQuery");
	}

	public function GetQuery ($params)
	{
		return sprintf (
			$$params ["ident"],
			$params ["params"]
		);
	}

	public function PopulateData ()
	{
	const BLOG_POPULATE_DATA = 
		" SELECT * FROM %s                    " .
		" WHERE id = %s                       " .
		" LIMIT 1                             " ;

	const CATEGORY_POPULATE_DATA = 
		" SELECT * FROM %s                    " .
		" WHERE blog = %s                     " .
		" AND id = %s                         " .
		" LIMIT 1                             " ;

	const CATEGORY_GET_ENTRIES = 
		" SELECT !.id FROM !, !               " .
		" WHERE !.category = ?                " .
		" AND !.id = !.entry                  " .
		" ORDER BY !.created ASC              " .
		" !                                   " ;

	const CATEGORY_CREATE_TABLE = 
		"CREATE TABLE ! (                     " .
		" id int(11) NOT NULL auto_increment, " .
		" blog int(11) NOT NULL,              " .
		" title text NOT NULL,                " .
		" PRIMARY KEY (id),                   " .
		" FULLTEXT KEY body (title)           " .
		") TYPE=MyISAM;                       " ;

	const CATEGORY_CREATE_NEW = 
		"INSERT INTO ! SET                    " .
		" blog = ?,                           " .
		" title = ?                           " ;

	const CATEGORY_DELETE = 
		" DELETE FROM !                       " .
		" WHERE id = ?                        " .
		" LIMIT 1                             " ;

	const CATEGORY_RELATIONSHIP_POPULATE_DATA = 
		" SELECT * FROM %s                    " .
		" WHERE entry = %s                    " .
		" AND category = %s                   " .
		" LIMIT 1                             " ;

	const CATEGORY_RELATIONSHIP_CREATE_TABLE = 
		"CREATE TABLE ! (                     " .
		" entry int(11) NOT NULL,             " .
		" category int(11) NOT NULL           " .
		") TYPE=MyISAM;                       " ;

	const CATEGORY_RELATIONSHIP_CREATE_NEW = 
		"INSERT INTO ! SET                    " .
		" entry = ?,                          " .
		" category = ?                        " ;

	const CATEGORY_RELATIONSHIP_DELETE = 
		" DELETE FROM !                       " .
		" WHERE entry = ?                     " .
		" AND category = ?                    " .
		" LIMIT 1                             " ;
}

?>
