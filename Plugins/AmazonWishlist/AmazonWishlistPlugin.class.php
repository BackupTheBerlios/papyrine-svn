<?php

	/**
	 * AmazonWishlistPlugin adds the display of a wishlist to the Papyrine weblogging system.
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
	class AmazonWishlistPlugin extends PapyrinePlugin
	{
		const WSDL = "http://soap.amazon.com/schemas2/AmazonWebServices.wsdl";
		const TOKEN = "http://soap.amazon.com/schemas2/AmazonWebServices.wsdl";

		function __construct ()
		{
			parent::__construct ("about.xml");
		}

		function __get ($var) 
		{
			switch
			{
				case "wishlist_id":
					return $this->xml->plugin->options->wishlist_id;
					break;
				case "associate_id":
					return $this->xml->plugin->options->associate_id;
					break;
				default:
					return parent::__get ($var);
			}
		}

		function __set ($var, $val)
		{
			switch
			{
				case "wishlist_id":
					$this->xml->plugin->options->wishlist_id = $val;
					break;
				case "associate_id":
					$this->xml->plugin->options->associate_id = $val;
					break;
			}
		}

		public function GetItems ($limit = 10)
		{
			$client = new SoapClient (self::WSDL);

			$params = array (
				"wishlist_id" => $this->wishlist_id,
				"page"        => 0,
				"type"        => "lite",
				"locale"      => "us",
				"tag"         => $this->associate_id,
				"devtag"      => self::TOKEN
			);

			$output = array ();
			$counter = $limit;

			while ($counter > 0)
			{
				$params ["page"] += 1;
				$items = $client->WishlistSearchRequest ($params);

				if (count ($items["details"]) <= $counter)
				{
					$output = array_merge ($output, $items["details"]);
					$counter -= count ($items["details"]);
				} else {
					$output = array_merge (
						$output, 
						array_slice ($items["details"], 0, $counter)
					);
					break;
				}
			}

			return $items["details"];
		}
	}

?>
