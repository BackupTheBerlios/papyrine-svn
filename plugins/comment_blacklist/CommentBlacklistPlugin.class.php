<?php

/**
 * CommentBlacklistPlugin adds a MT-Blacklist based filter to Papyrine.
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
class CommentBlacklistPlugin extends PapyrinePlugin
{
	const Blacklist = "http://www.jayallen.org/comment_spam/blacklist.txt";
	const table = "papyrine_blacklist";

	function __construct ()
	{
		parent::__construct ("about.xml");

		Papyrine::RegisterHook ("on_comment_post", 
		                        "CommentBlacklistPlugin::CheckComment");
	}

	public function FirstRun ()
	{
		self::CreateTable ($this->database);

		$this->UpdateDatabase ();
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @return boolean
	 * @uses PapyrineEntry::table
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function CreateTable (&$database)
	{
		$result = $database->query (
			"CREATE TABLE ! (                     " .
			" id int(11) NOT NULL auto_increment, " .
			" regex text NOT NULL,                " .
			" type text NOT NULL,                 " .
			" PRIMARY KEY (id)                    " .
			") TYPE=MyISAM;                       " ,
			array (
				self::table
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}

	public function UpdateDatabase ()
	{
		$domain = Papyrine::GetFile (self::Blacklist);

		for ($i = 0; $i < count ($domain); $i++)
		{
			$data = $domain[$i];
			$temp = "";

			for ($j = 0; $j < strlen ($data); $j++)
			{
				if ($data[$j] == " " || $data[$j] == "#")
					break;
				else
					$temp .= $data[$j];

				continue;
			}

			// modify Jay Allen stuff to work with PHP
			if (strpos ($temp, '[\w\-_.]'))
				$temp = str_replace ('[\w\-_.]','[-\w\_.]', $temp);

			if (strpos ($temp, '/f'))
			{
				$p = strpos ($temp, '/f');
				$temp = substr ($temp, $p+1);
			}

			$temp = trim ($temp);

			if ($temp!="")
			{
				$request = $wpdb->get_row("SELECT id FROM blacklist WHERE regex='$temp'");

				if (!$request) 
					$request1 = $wpdb->query("INSERT INTO blacklist (regex,regex_type) VALUES ('$temp','url')");
			}
		}
	}

	public function CheckComment ($params)
	{
		$result = $this->database->query (
			" SELECT regex FROM ! " ,
			array (
				self::table
			)
		);

		while ($row =& $result->fetchRow ()) 
		{
            $regex = "/" . $row ["regex"] . "/i";

			if ($row ["type"] == "url")
			{
				if ((preg_match ($regex, $params ["url"]) ||
				    (preg_match ($regex, $params ["email"]) ||
				    (preg_match ($regex, $params ["comment"]))
				{
					$result->free ();

				    return false;
				}
			} 
			elseif ($row ["type"] == "ip")
			{
				if (strcmp ($regex, $_SERVER ["REMOTE_ADDR"]) == 0)
				{
					$result->free ();

				    return false;
				}
			}

		}

		$result->free ();

		return true;
	}
}

?>
