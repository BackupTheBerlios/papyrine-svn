<?php

/**
 * Papyrine is a weblogging system built using PHP5 and Smarty.
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
 * @subpackage Classes
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Decribes a Papyrine user.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineUser extends PapyrineObject
{
	/**
	 * Name of the database table to map this object to.
	 *
	 * @var string 
	 */
	const TABLE = "papyrine_users";

	/**
	 * PapyrineUser constructor.
	 *
	 * @param integer|string $id User's unique id or username.
	 * @param mixed $database Reference for already opened database.
	 * @uses PapyrineUser::TABLE
	 */
	function __construct (&$database, $id) 
	{
		// Initial PapyrineObject.
		parent::__construct ($database, self::TABLE);

		// How to populate data.
		$this->sql = sprintf (
			" SELECT * FROM %s " .
			" WHERE %s = %s    " .
			" LIMIT 1          " ,
			self::TABLE,
			(is_numeric ($this->id) ? "id" : "nickname"),
			$this->id
		);
	}

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @return boolean
	 * @uses PapyrineUser::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function CreateTable (&$database)
	{
		$result = $database->query (
			"CREATE TABLE ! (                            " .
			" id int(11) NOT NULL auto_increment,        " .
			" blog int(11) NOT NULL,                     " .
			" nickname text NOT NULL,                    " .
			" password text NOT NULL,                    " .
			" firstname text NOT NULL,                   " .
			" lastname text NOT NULL,                    " .
			" email text NOT NULL,                       " .
			" nameformat int(11) NOT NULL default '0',   " .
			" modifier int(11) NOT NULL default '0',     " .
			" notification int(11) NOT NULL default '0', " .
			" PRIMARY KEY (id)                           " .
			") TYPE=MyISAM;                              " ,
			array (
				self::TABLE
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}

	/**
	 * Create a new user.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $blog Unique id of this blog.
	 * @param string $nickname New user's nickname.
	 * @param string $password New user's password.
	 * @param string $firstname New user's first name.
	 * @param string $lastname New user's last name.
	 * @param string $email New user's email address.
	 * @return boolean
	 * @uses PapyrineUser::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function Create (&$database, $blog, $nickname, $password, 
	                               $firstname, $lastname, $email)
	{
		// Generate the query and insert into the database.
		$result = $database->query (
			"INSERT INTO ! SET " .
			" blog = ?,        " .
			" nickname = ?,    " .
			" password = ?,    " .
			" firstname = ?,   " .
			" lastname = ?,    " .
			" email = ?        " ,
			array (
				self::TABLE,
				$blog,
				$nickname,
				md5 ($password),
				$firstname,
				$lastname,
				$email
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}

	/**
	 * See if the supplied password equals the hash of our records.
	 *
	 * @param string $password
	 * @return boolean
	 */
	public function ValidatePassword ($password)
	{
		return ($this->data["password"] == md5 ($password));
	}

	/**
	 * Delete the user.
	 *
	 * @return boolean
	 * @uses PapyrineUser::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public function Delete ()
	{
		$result = $this->database->query (
			" DELETE FROM ! " .
			" WHERE id = ?  " .
			" LIMIT 1       " ,
			array (
				self::TABLE,
				$this->data["id"]
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}
}

?>
