<?php

	/**
	 * TexturizePlugin adds the Texturize modifier to Papyrine.
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
	class TexturizePlugin extends PapyrinePlugin
	{
		function __construct ()
		{
			parent::__construct ("about.xml");
		}

		/**
		 * Convience function to convert using texturize.
		 *
		 * @param string $text Text to be converted.
		 * @return string
		 */
		public function Convert ($text)
		{
			return $this->texturize ($text)
		}

		private function texturize ($text) 
		{
			$textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
			$stop = count($textarr); $next = true; // loop stuff

			for ($i = 0; $i < $stop; $i++)
			{
				$curl = $textarr[$i];
				if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Gecko')) 
				{
					$curl = str_replace('<q>', '&#8220;', $curl);
					$curl = str_replace('</q>', '&#8221;', $curl);
				}

				if ('<' != $curl{0} && $next) 
				{ // If it's not a tag
					$curl = str_replace('---', '&#8212;', $curl);
					$curl = str_replace('--', '&#8211;', $curl);
					$curl = str_replace("...", '&#8230;', $curl);
					$curl = str_replace('``', '&#8220;', $curl);

					// This is a hack, look at this more later. It works pretty well though.
					$cockney = array("'tain't","'twere","'twas","'tis","'twill","'til","'bout","'nuff","'round", "'em");
					$cockneyreplace = array("&#8217;tain&#8217;t","&#8217;twere","&#8217;twas","&#8217;tis","&#8217;twill","&#8217;til","&#8217;bout","&#8217;nuff","&#8217;round","&#8217;em");
					$curl = str_replace($cockney, $cockneyreplace, $curl);

					$curl = preg_replace("/'s/", "&#8217;s", $curl);
					$curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $curl);
					$curl = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $curl);
					$curl = preg_replace("/(\d+)\"/", "$1&Prime;", $curl);
					$curl = preg_replace("/(\d+)'/", "$1&prime;", $curl);
					$curl = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $curl);
					$curl = preg_replace('/"([\s.]|\Z)/', '&#8221;$1', $curl);
					$curl = preg_replace('/(\s|\A)"/', '$1&#8220;', $curl);
					$curl = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $curl);
					$curl = preg_replace("/\(tm\)/i", '&#8482;', $curl);
					$curl = preg_replace("/\(c\)/i", '&#169;', $curl);
					$curl = preg_replace("/\(r\)/i", '&#174;', $curl);

					$curl = str_replace("''", '&#8221;', $curl);
					$curl = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $curl);

					$curl = preg_replace('/(d+)x(\d+)/', "$1&#215;$2", $curl);
				} elseif (strstr($curl, '<code') || strstr($curl, '<pre') || strstr($curl, '<kbd' || strstr($curl, '<style') || strstr($curl, '<script'))) // strstr is fast
					$next = false;
				else
					$next = true;

				$output .= $curl;
			}

			return $output;
		}
	}

?>
