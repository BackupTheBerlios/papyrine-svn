<?php

	/**
	 * BlogsPlugin adds the blo.gs blogroll to the Papyrine weblogging system.
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
	class BlogsPlugin extends PapyrinePlugin
	{
		private	$local  = "/Data/favorites.rss";

		function __construct ()
		{
			parent::__construct ("about.xml");
		}

		function __get ($var) 
		{
			switch
			{
				case "userid":
					return $this->xml->plugin->options->userid;
					break;
				default:
					return parent::__get ($var);
			}
		}

		function __set ($var, $val)
		{
			switch
			{
				case "userid":
					$this->xml->plugin->options->userid = $val;
					break;
			}
		}

		public function GetBlogs ($limit = 10)
		{
			$file = $this->GetFavoritesRSS ();
			$xml  = simplexml_load_string ($file);

			$output  = array ();
			$counter = 0;

			foreach ($xml->rss->channel->item as $item)
			{
				if ($counter > $limit)
					break;

				$output[] = array (
					'title'     => $item->title,
					'published' => $item->pubDate,
					'url'       => $item->link
				);

				$counter++;
			}

			return $output;
		}

		private function GetFavoritesRSS ()
		{
			$remote = "http://blo.gs/" . $this->userid . "/favorites.xml";

			// fresh enough to use? give it a sniff
			if (filemtime ($local) < (time() - 3600))
			{
				if ($blogroll_local_fq = fopen($blogroll_xml_file,"w") )
				{
			    	$blogroll_remote_fp = fopen($blogroll_xml_source,"r");
			    	$blogroll_remote_data = fread($blogroll_remote_fp, 100000);
			    	if (stristr($blogroll_remote_data, $blogroll_xml_test))
					{
						if ($blogroll_remote_fp && $blogroll_local_fq)
			        		fwrite($blogroll_local_fq,$blogroll_remote_data);
					}
					fclose ($blogroll_remote_fp);
					fclose ($blogroll_local_fq);
				}
			}

			return $this->local;
		}
	}

?>
