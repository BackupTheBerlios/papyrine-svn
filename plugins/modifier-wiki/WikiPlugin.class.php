<?php

/**
 * WikiPlugin adds the Wiki modifier to Papyrine.
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
 * @subpackage Plugins
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Plugins
 */
class WikiPlugin extends PapyrinePlugin implements PapyrineModifier
{
	const TITLE       = "Wiki Modifier Plugin";
	const DESCRIPTION = "Coverts from Wiki markup to XHTML";
	const WEBSITE     = "http://c2.com/cgi/wiki";
	const VERSION     = 0.1;

	public static function ModifyText ($text)
	{
		require_once ("Wiki.php");

		// instantiate a Text_Wiki object with the default rule set
		$wiki =& new Text_Wiki ();

		// transform the wiki text into XHTML
		return $wiki->transform ($text, 'Xhtml');
	}
}

?>
