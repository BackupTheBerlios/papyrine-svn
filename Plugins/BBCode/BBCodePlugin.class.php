<?php

	/**
	 * BBCodePlugin adds the BBCode modifier to Papyrine.
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
	class BBCodePlugin extends PapyrinePlugin
	{
		function __construct ()
		{
			parent::__construct ("about.xml");
		}

		/**
		 * Convience function to convert using bbcode.
		 *
		 * @param string $text Text to be converted.
		 * @return string
		 */
		public function Convert ($text)
		{
			return $this->bbcode ($text)
		}

		private function bbcode ($message) 
		{
			$preg = array(
				// Font and text manipulation ( [color] [size] [font] [align] )
				'/\[color=(.*?)(?::\w+)?\](.*?)\[\/color(?::\w+)?\]/si'   => "<span style=\"color:\\1\">\\2</span>",
				'/\[size=(.*?)(?::\w+)?\](.*?)\[\/size(?::\w+)?\]/si'     => "<span style=\"font-size:\\1\">\\2</span>",
				'/\[font=(.*?)(?::\w+)?\](.*?)\[\/font(?::\w+)?\]/si'     => "<span style=\"font-family:\\1\">\\2</span>",
				'/\[align=(.*?)(?::\w+)?\](.*?)\[\/align(?::\w+)?\]/si'   => "<div style=\"text-align:\\1\">\\2</div>",
				'/\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si'                 => "<b>\\1</b>",
				'/\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si'                 => "<i>\\1</i>",
				'/\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si'                 => "<u>\\1</u>",
				'/\[center(?::\w+)?\](.*?)\[\/center(?::\w+)?\]/si'       => "<div style=\"text-align:center\">\\1</div>",
				'/\[code(?::\w+)?\](.*?)\[\/code(?::\w+)?\]/si'           => "<div class=\"ng_code\">\\1</div>",
				// [email]
				'/\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'         => "<a href=\"mailto:\\1\" class=\"ng_email\">\\1</a>",
				'/\[email=(.*?)(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'   => "<a href=\"mailto:\\1\" class=\"ng_email\">\\2</a>",
				// [url]
				'/\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si'        => "<a href=\"http://www.\\1\"  class=\"ng_url\">\\1</a>",
				'/\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "<a href=\"\\1\"  class=\"ng_url\">\\1</a>",
				'/\[url=(.*?)(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'       => "<a href=\"\\1\"  class=\"ng_url\">\\2</a>",
				// [img]
				'/\[img(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si'             => "<img src=\"\\1\" border=\"0\" />",
				'/\[img=(.*?)x(.*?)(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si' => "<img width=\"\\1\" height=\"\\2\" src=\"\\3\" border=\"0\" />",
				// [quote]
				'/\[quote(?::\w+)?\](.*?)\[\/quote(?::\w+)?\]/si'         => "<div class=\"ng_quote\">Quote:<div class=\"ng_quote_body\">\\1</div></div>",
				'/\[quote=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](.*?)\[\/quote(?::\w+)?\]/si'   => "<div class=\"ng_quote\">Quote \\1:<div class=\"ng_quote_body\">\\2</div></div>",
				// [list]
				'/\[\*(?::\w+)?\]\s*([^\[]*)/si'                          => "<li class=\"ng_list_item\">\\1</li>",
				'/\[list(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/si'           => "<ul class=\"ng_list\">\\1</ul>",
				'/\[list(?::\w+)?\](.*?)\[\/list:u(?::\w+)?\]/s'          => "<ul class=\"ng_list\">\\1</ul>",
				'/\[list=1(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/si'         => "<ol class=\"ng_list\" style=\"list-style-type:decimal;\">\\1</ol>",
				'/\[list=i(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/s'          => "<ol class=\"ng_list\" style=\"list-style-type:lower-roman;\">\\1</ol>",
				'/\[list=I(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/s'          => "<ol class=\"ng_list\" style=\"list-style-type:upper-roman;\">\\1</ol>",
				'/\[list=a(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/s'          => "<ol class=\"ng_list\" style=\"list-style-type:lower-alpha;\">\\1</ol>",
				'/\[list=A(?::\w+)?\](.*?)\[\/list(?::\w+)?\]/s'          => "<ol class=\"ng_list\" style=\"list-style-type:upper-alpha;\">\\1</ol>",
				'/\[list(?::\w+)?\](.*?)\[\/list:o(?::\w+)?\]/s'          => "<ol class=\"ng_list\" style=\"list-style-type:decimal;\">\\1</ol>",
				// the following lines clean up our output a bit
				'/<ol(.*?)>(?:.*?)<li(.*?)>/si'         => "<ol\\1><li\\2>",
				'/<ul(.*?)>(?:.*?)<li(.*?)>/si'         => "<ul\\1><li\\2>"
			);
			$message = preg_replace(array_keys($preg), array_values($preg), $message);

			// make clickable() :
			/**
			 * Rewritten by Nathan Codding - Feb 6, 2001.
			 * - Goes through the given string, and replaces xxxx://yyyy with an HTML <a> tag linking
			 * 	to that URL
			 * - Goes through the given string, and replaces www.xxxx.yyyy[zzzz] with an HTML <a> tag linking
			 * 	to http://www.xxxx.yyyy[/zzzz]
			 * - Goes through the given string, and replaces xxxx@yyyy with an HTML mailto: tag linking
			 *		to that email address
			 * - Only matches these 2 patterns either after a space, or at the beginning of a line
			 *
			 * Notes: the email one might get annoying - it's easy to make it more restrictive, though.. maybe
			 * have it require something like xxxx@yyyy.zzzz or such. We'll see.
			 */

			// pad it with a space so we can match things at the start of the 1st line.
			$ret = ' ' . $message;

			// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
			// xxxx can only be alpha characters.
			// yyyy is anything up to the first space, newline, comma, double quote or <
			$ret = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3">\2://\3</a>', $ret);

			// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
			// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
			// zzzz is optional.. will contain everything up to the first space, newline,
			// comma, double quote or <.
			$ret = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3">\2.\3</a>', $ret);

			// matches an email@domain type address at the start of a line, or after a space.
			// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
			$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);

			// Remove our padding..
			$ret = substr($ret, 1);

			return (nl2br($ret));
		}
	}

?>
