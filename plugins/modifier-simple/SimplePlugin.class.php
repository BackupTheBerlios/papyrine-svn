<?php

/**
 * SimplePlugin adds a basic formatting modifier to Papyrine.
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
class SimplePlugin extends PapyrinePlugin implements PapyrineModifier
{
	const TITLE       = "Simple Modifier Plugin";
	const DESCRIPTION = "Coverts urls and newlines to XHTML";
	const WEBSITE     = "";
	const VERSION     = 0.1;

	public static function ModifyText ($text)
	{
		// pad it with a space so we can match things at the start of the 1st line.
		$ret = ' ' . $ret;

		// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
		// xxxx can only be alpha characters.
		// yyyy is anything up to the first space, newline, comma, double quote or <
		$ret = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" target="_blank">\2://\3</a>', $ret);

		// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
		// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
		// zzzz is optional.. will contain everything up to the first space, newline,
		// comma, double quote or <.
		$ret = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" target="_blank">\2.\3</a>', $ret);

		// matches an email@domain type address at the start of a line, or after a space.
		// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
		$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);

		// Remove our padding..
		$ret = substr($ret, 1);

	    return nl2br($ret);
	}
}

?>
