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
 * A general interface for accessing a Papyrine entry.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class PapyrineEntry
{
	private $blog;
	private $id;

	/**
	 * Array of information describing this object.
	 *
	 * @var array|boolean
	 */
	protected $data = false;

	/**
	 * Contains the changes that need to be sync'd with the database.
	 *
	 * @var array 
	 */
	protected $mod = array ();

	/**
	 * PapyrineEntry class constructor.
	 *
	 * @param integer $blog The entry's unique blog id.
	 * @param integer|string $id The entry's unique id.
	 */
	function __construct ($blog, $id)
	{
		$this->id   = $id;
		$this->blog = $blog;
	}

	/**
	 * Automatically update the "modified" value.
	 */
	function __destruct () 
	{
		if (count ($this->mod) > 0)
		{
			if (array_key_exists ("body", $this->mod))
			{
				$this->data ["modified"] = $val;
				$this->mod  ["modified"] = true;
			}
		}
	}

	/**
	 * Create a new comment for this entry.
	 *
	 * @param string $body Comment text.
	 * @param string $owner_name Comment poster's name.
	 * @param string $owner_email Comment poster's email.
	 * @return boolean
	 * @uses PapyrineComment::Create()
	 */
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
	}

	/**
	 * Get an array of comments for this entry.
	 * 
	 * @return array
	 * @uses PapyrineComment::TABLE
	 * @uses DB_common::query
	 * @uses DB_result::fetchRow
	 * @uses DB_result::free
	 */
	public function GetComments ()
	{
		$result = $this->database->query (
			" SELECT id FROM !     " .
			" WHERE entry = ?      " .
			" ORDER BY created ASC " ,
			array (
				PapyrineComment::TABLE,
				$this->data["id"]
			)
		);

		$comments = array ();
		while ($row =& $result->fetchRow ()) 
			$comments[] = new PapyrineComment ($this->database, $row ["id"]);

		$result->free ();

		return $comments;
	}

	/**
	 * Get the entry owner as an object.
	 *
	 * @return PapyrineUser
	 */
	public function GetOwner ()
	{
		return new PapyrineUser ($this->data["owner"]);
	}

	/**
	 * Add a new category for this entry.
	 *
	 * @return boolean
	 * @param integer $category The unique id of the category.
	 */
	public function AddCategory ($category)
	{
		return PapyrineCategoryRelationship::Create ($this->database, 
		                                             $this->data["id"],
		                                             $category)
	}

	/**
	 * Remove a new category from this entry.
	 *
	 * @return boolean
	 * @param integer $category The unique id of the category.
	 */
	public function RemoveCategory ($category)
	{
		$relationship = new PapyrineCategoryRelationship ($this->database, 
		                                                  $this->data["id"], 
		                                                  $category);
		return $relationship->Delete ();
	}

	/**
	 * Get an array of categories for this entry.
	 *
	 * @return array
	 * @uses PapyrineCategory::TABLE
	 * @uses PapyrineCategoryRelationship::TABLE
	 * @uses DB_common::query
	 * @uses DB_result::fetchRow
	 * @uses DB_result::free
	 */
	public function GetCategories ()
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

	/**
	 * Get the entry immediatly after this one.
	 *
	 * @return PapyrineEntry
	 * @uses PapyrineEntry::TABLE
	 * @uses DB_common::getOne
	 */
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

	/**
	 * Get the entry immediatly before this one.
	 *
	 * @return PapyrineEntry
	 * @uses PapyrineEntry::TABLE
	 * @uses DB_common::getOne
	 */
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

	/**
	 * Create the database table. For Papyrine installation only.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @return boolean
	 * @uses PapyrineEntry::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public static function CreateTable (&$database)
	{
		$result = $database->query (
			"CREATE TABLE ! (                        " .
			" id int(11) NOT NULL auto_increment,    " .
			" blog int(11) NOT NULL,                 " .
			" title text NOT NULL,                   " .
			" linktitle text NOT NULL,               " .
			" summary text NOT NULL,                 " .
			" body text NOT NULL,                    " .
			" created timestamp(14) NOT NULL,        " .
			" modified timestamp(14) NOT NULL,       " .
			" status int(11) NOT NULL,               " .
			" owner int(11) NOT NULL,                " .
			" onfrontpage int(11) NOT NULL,          " .
			" allowcomments int(11) NOT NULL,        " .
			" autodisable timestamp(14) NOT NULL,    " .
			" comments int(11) NOT NULL default '0', " .
			" PRIMARY KEY (id),                      " .
			" FULLTEXT KEY title (title),            " .
			" FULLTEXT KEY body (body)               " .
			") TYPE=MyISAM;                          " ,
			array (
				self::TABLE
			)
		);

		$result->free ();

		return !DB::isError ($result);
	}

	/**
	 * Create a new entry.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param integer $blog Unique id of this blog.
	 * @param string $title New entry's title.
	 * @param string $summary New entry's summary.
	 * @param string $body New entry's body of text.
	 * @param integer $owner New entry's creator.
	 * @param integer $status New entry's status. 0=draft, 1=live.
	 * @param integer $onfrontpage Whether the entry should be on the frontpage.
	 * @param integer $allowcomments Should commenting be allowed.
	 * @param string $autodisable Timestamp to disable comments at.
	 * @return boolean
	 * @uses PapyrineEntry::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_common::quoteSmart
	 * @uses DB_result::free
	 */
	public static function Create (&$database, $blog, $title, $summary, $body, 
	                               $owner, $status = true, $onfrontpage = true,
	                               $allowcomments = true, $autodisable = false)
	{
		// Generate the query and insert into the database.
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

		return !DB::isError ($result);
	}

	private function PopulateData ()
	{
		Papyrine::ExecuteHooks (
			"entry_populate_data", 
			array (
				"id"  => $id
			),
			$this->data
		);
	}

	/**
	 * Delete the entry.
	 *
	 * @return boolean
	 * @uses PapyrineEntry::TABLE
	 * @uses DB::isError
	 * @uses DB_common::query
	 * @uses DB_result::free
	 */
	public function Delete ()
	{
		Papyrine::ExecuteHooks (
			"entry_delete", 
			array (
				"id"  => $id
			),
			$this->data,
			$output
		);

		return $output;
	}
}

?>
