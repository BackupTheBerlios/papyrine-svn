<?php

	/**
	 * GoogleRedirectPlugin adds a PageRank stripper to Papyrine.
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
	class GoogleRedirectPlugin extends PapyrinePlugin
	{
		const GoogleRedirectURL = "http://www.google.com/url?sa=D&q=%s";

		function __construct ()
		{
			parent::__construct ("about.xml");

			Papyrine::RegisterHook ("on_url_modify", 
			                        "GoogleRedirectPlugin::StripPageRank");
		}

		public static function StripPageRank ($params)
		{
			return sprintf (self::GoogleRedirectURL, 
			                urlencode ($params ["url"]));
		}
	}

?>
