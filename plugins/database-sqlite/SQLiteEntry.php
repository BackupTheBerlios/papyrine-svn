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
 * Decribes a Papyrine entry.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage SQLiteDatabasePlugin
 */
class SQLiteEntry implements PapyrineEntry
{
	const TABLE = 'papyrine_entries';
	private $_id;
	private $_data = false;

	function __construct (integer $blog, string $id)
	{
		$this->_id = array (
			"blog" => $blog,
			"id"   => $id
		);
	}

	private function _Populate ()
	{
		global $papyrine;

		$sql = sprintf (
			" SELECT                                                     " .
			"  id, entry, body, owner_name, owner_email, status, created " .
			" FROM %s WHERE                                              " .
			"  blog = %s AND id = %s                                     " .
			" LIMIT 1                                                    " ,
			self::TABLE,
			$this->_id ["blog"],
			$this->_id ["id"]
		);

		$this->_data = $papyrine->database->connection->arrayQuery (
			$sql,
			SQLITE_ASSOC
		);
	}

	function __get ($var)
	{
		if ($this->_data == false)
			$this->_Populate ();

		if (isset ($this->_data[$var]))
			return $this->_data [$var];
	}

	function __set ($var, $val)
	{
		//
	}

	public static function Create (string $title, string $summary, 
	                               string $body, integer $owner, 
	                               boolean $status = true, 
	                               boolean $onfrontpage = true, 
	                               boolean $allowcomments = true, 
	                               boolean $autodisable = false)
	{
		/* Generate the query and insert into the database.
		$result = $database->query (
			"INSERT INTO ! SET   " .
			" blog = ?,          " .
			" title = ?,         " .
			" linktitle = ?,     " .
			" summary = ?,       " .
			" body = ?,          " .
			" owner = ?,         " .
			" status = ?,        " .
			" onfrontpage = ?,   " .
			" allowcomments = ?, " .
			" autodisable = ?,   " .
			" created = NOW(),   " .
			" modified = NOW()   " ,
			array (
				self::TABLE,
				$blog,
				$title,
				$title,
				$summary,
				$body,
				$owner,
				($status ? 1 : 0),
				($onfrontpage ? 1 : 0),
				($allowcomments ? 1 : 0),
				($autodisable ? "FROM_UNIXTIME(" . $autodisable . ")" : 0)
			)
		);

		$result->free ();

		return !DB::isError ($result);*/
	}

	public function GetID ()
	{
		return $this->__get ("id");
	}

	public function GetTitle ()
	{
		return $this->__get ("title");
	}

	public function GetSummary ()
	{
		return $this->__get ("summary");
	}

	public function GetBody ()
	{
		return $this->__get ("body");
	}

	public function GetOwner ()
	{
		return new SQLitePapyrineUser ($this->__get ("owner"));
	}

	public function GetStatus ()
	{
		return $this->__get ("status");
	}

	public function GetOnFrontpage ()
	{
		return $this->__get ("onfrontpage");
	}

	public function GetAllowComments ()
	{
		return $this->__get ("allowcomments");
	}

	public function GetAutoDisable ()
	{
		return $this->__get ("autodisable");
	}

	public function SetTitle (string $title)
	{
		return $this->__set ("title", $title);
	}

	public function SetSummary (string $summary)
	{
		return $this->__set ("summary", $summary);
	}

	public function SetBody (string $body)
	{
		return $this->__set ("body", $body);
	}

	public function SetOwner (integer $owner)
	{
		return $this->__set ("owner", $owner);
	}

	public function SetStatus (integer $status)
	{
		return $this->__set ("status", $status);
	}

	public function SetOnFrontpage (integer $onfrontpage)
	{
		return $this->__set ("onfrontpage", $onfrontpage);
	}

	public function SetAllowComments (integer $allowcomments)
	{
		return $this->__set ("allowcomments", $allowcomments);
	}

	public function SetAutoDisable (integer $autodisable)
	{
		return $this->__set ("autodisable", $autodisable);
	}

	/**
	 * Delete the category.
	 */
	public function Delete ()
	{
		global $papyrine;

		$sql = sprintf (
			" DELETE FROM %s " .
			" AND id = %s    " .
			" LIMIT 1        " ,
			self::TABLE,
			$this->_id ["id"]
		);

		$papyrine->database->connection->unbufferedQuery ($sql);
	}
}
/*
	public function CreateComment ($body, $owner_name, $owner_email)
	{			
		$this->data["comments"] += 1;
		$this->mod["comments"]   = true;

		return PapyrineComment::Create (
			$this->database, 
			$this->data["id"],
			$body, 
			$owner_name, 
			$owner_email
		);
	}	public function GetCategories ()
	{
		$result = $this->database->query (
			" SELECT !.category FROM !, ! " .
			" WHERE !.id = !.category     " .
			" AND !.entry = ?             " .
			" ORDER BY !.title ASC        " ,
			array (
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategory::TABLE,
				PapyrineCategory::TABLE,
				PapyrineCategoryRelationship::TABLE,
				PapyrineCategoryRelationship::TABLE,
				$this->data["id"],
				PapyrineCategory::TABLE
			)
		);

		$categories = array ();
		while ($row =& $result->fetchRow ())
		{
			$categories[] = new PapyrineCategory ($this->database, 
			                                      $row ["category"]);
		}

		$result->free ();

		return $categories;
	}

	public function GetNext ()
	{
		$sql = " SELECT id FROM !     " .
		       " WHERE blog = ?       " .
		       " AND status = ?       " .
		       " AND created > ?      " .
		       " ORDER BY created ASC " .
		       " LIMIT 1              " ;

		$params = array (
			self::TABLE,
			$this->data["blog"],
			$this->data["status"],
			$this->data["created"]
		);

		return new PapyrineEntry ($this->database, 
		                          $this->database->getOne ($sql, $params));
	}

	public function GetPrevious ()
	{
		$sql = " SELECT id FROM !      " .
		       " WHERE blog = ?        " .
		       " AND status = ?        " .
		       " AND created < ?       " .
		       " ORDER BY created DESC " .
		       " LIMIT 1               " ;

		$params = array (
			self::TABLE,
			$this->data["blog"],
			$this->data["status"],
			$this->data["created"]
		);

		return new PapyrineEntry ($this->database,
		                          $this->database->getOne ($sql, $params));
	}
*/
?>
