<?php

/**
 * AllConsumingPlugin add reading lists to the Papyrine weblogging system.
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
class AllConsumingPlugin extends PapyrinePlugin
{
	const WSDL  = "http://soap.amazon.com/schemas2/AmazonWebServices.wsdl";
	const TABLE = "papyrine_allconsuming";

	private $data;

	function __construct ()
	{
		parent::__construct ();

		$this->data = $this->database->getRow (
			" SELECT * FROM ! " .
//			" WHERE blog = ?  " .
			" LIMIT 1         " ,
			array (
				self::TABLE
				//blogid
			)
		);
	}

	function __get ($var) 
	{
		switch
		{
			case "username":
				return $this->data ["username"];
				break;
		}
	}

	function __set ($var, $val)
	{
		switch
		{
			case "username":
				$result = $this->database->query (
					" UPDATE !         " .
					" SET username = ? " .
//					" WHERE blog = ?   " .
					" LIMIT 1          " ,
					array (
						self::TABLE,
						$val,
						//blogid
					)
				);

				$result->free ();

				if (!DB::isError ($result))
					$this->data ["username"] = $val;

				break;
		}
	}

	public static function CreateTable (&$database)
	{
		$result = $database->query (
			"CREATE TABLE ! (        " .
//			" blog int(11) NOT NULL, " .
			" username text NOT NULL " .
			") TYPE=MyISAM;          " ,
			array (
				self::TABLE
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}

	public function GetBooks ($limit = 10)
	{
	}
}

?>
