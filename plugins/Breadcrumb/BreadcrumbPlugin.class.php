<?php

/**
 * BreadcrumbPlugin adds a breadcrumb to Papyrine.
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
class BreadcrumbPlugin extends PapyrinePlugin
{
	function __construct ()
	{
	}

	/**
	 * Get an array with the breadcrumb for the current page.
	 *
	 * @return array
	 */
	public static function BuildCrumbs ()
	{
		// Initialize our output array.
		$crumbs = array ();

		// Get an array of the various "layers" of the current page.
		$parts = explode ("/", URL);

		// For each part of the URL.
		for ($i = 0; $i < count ($parts); $i++)
		{
			// Get the possible options for this level.
			$options = GetPossibilitiesAtLevel ($i);

			// For each of the options
			foreach ($options as $option)
			{
				// Check which option for this level works.
				if (str_reg ($option, $part))
				{
					// Add the level to the output crumbs.
					$crumbs [] = array (
						"title" => ,
						"url"   =>
					);

					continue;
				}
			}
		}

		// Return the breadcrumbs.
		return $crumbs;
	}
}

?>
