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
 * Autoload classes as needed.
 */
function __autoload ($class)
{
	require_once ($class . ".class.php");
}

/**
 * The central Papyrine object, hands database initialization.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
class Papyrine extends Smarty
{
	/**
	 * Our prized database connection.
	 *
	 * @var mixed 
	 */
	private $database_con = false;

	/**
	 * An array of registered hooks.
	 *
	 * @var array 
	 */
	private $hooks = array ();

   	/**
   	 * The class destructor, closes the database connection if open.
   	 */
	function __destruct () 
	{
		$this->database_con->disconnect();
	}

   	/**
   	 * If we ask for the database and we don't have it, create one.
   	 */
	function __get ($var) 
	{
		if ($var == "database")
		{
			if (!$this->database_con)
				$this->database_con =& Papyrine::connect ();
			else
				return $this->database_con;
		}
	}

	/**
	 * Static database connection so we don't need to carry the location
	 * of the database file around.
	 */
	public static function connect ($file)
	{
		$dsn = array(
    		"phptype"  => "sqlite",
		    "hostspec" => "/" . $_SERVER["DOCUMENT_ROOT"] . "Data/" . $file
		);

		return DB::connect ($dsn);
	}

	/**
	 * If we are going to use smarty, set it up.
	 *
	 * @uses PapyrinePlugins::GetSmartyLocations
	 * @uses PapyrinePlugins::GetTemplateLocations
	 */
	public function InitializeSmarty ()
	{
		if (!$this->smarty)
		{
			parent:__construct ();

			// Make loaded plugins visible.
			$this->plugins_dir  = array_push (
				PapyrinePlugins::GetSmartyLocations (), 
				"Papyrine_plugins/", 
				"core/", 
				"plugins/"
			);

			// Make loaded plugins visible.
			$this->template_dir  = array_push (
				PapyrinePlugins::GetTemplateLocations (), 
				"Templates/"
			);

			$this->compile_dir  = "Data/Compiled/";
			$this->assign ('database', $this->database);
			$this->assign ('system', array (
				'url'     => '',
				'name'    => 'Papyrine',
				'version' => 0.1)
			);
		}
	}

	/**
	 * Create a new category.
	 *
	 * @param string $blog The blog to create a category for.
	 * @param string $title The category's title.
	 * @return integer
	 * @uses PapyrineBlog::CreateCategory
	 */
	public function CreateCategory ($blog, $title)
	{
		$blog = new PapyrineBlog ($this->database, $blog);

		return $blog->CreateCategory (
			$this->database,
			$title
		);
	}

	/**
	 * Get a category by id.
	 *
	 * @param string $blog The blog to get a category for.
	 * @param integer $id Category's unique id.
	 * @return PapyrineCategory
	 */
	public function GetCategory ($blog, $id)
	{
		$blog = new PapyrineBlog ($this->database, $blog);

		return $blog->GetCategory ($this->database, $id);
	}

	/**
	 * Get a comment by id.
	 *
	 * @param integer $id Comment's unique id.
	 * @return PapyrineComment
	 */
	public function GetComment ($id)
	{
		return new PapyrineComment ($this->database, $id);
	}

	/**
	 * Get a category relationship.
	 *
	 * @param integer $entry Relationship's entry id.
	 * @param integer $category Relationship's category id.
	 * @return PapyrineCategoryRelationship
	 */
	public function GetCategoryRelationship ($entry, $category)
	{
		return new PapyrineCategoryRelationship ($this->database, $entry,
		                                         $category);
	}

	/**
	 * Create a new entry.
	 *
	 * @param mixed $database Reference for already opened database.
	 * @param string $title New entry's title.
	 * @param string $summary New entry's summary.
	 * @param string $body New entry's body of text.
	 * @param integer $owner New entry's creator.
	 * @param integer $status New entry's status. 0=draft, 1=live.
	 * @param integer $onfrontpage Whether the entry should be on the frontpage.
	 * @param integer $allowcomments Should commenting be allowed.
	 * @param string $autodisable Timestamp to disable comments at.
	 * @return integer
	 * @uses PapyrineEntry::Create
	 */
	public function CreateEntry ($title, $summary, $body, $owner, $status = true, $onfrontpage = true, $allowcomments = true, $autodisable = false)
	{
		return PapyrineEntry::Create (
			$this->database, 
			$this->data["id"],
			$title,
			$summary,
			$body,
			$owner,
			$status,
			$onfrontpage,
			$allowcomments,
			$autodisable
		);
	}

	/**
	 * Get a entry by id.
	 *
	 * @param integer $id Entry's unique id.
	 * @return PapyrineEntry
	 */
	public function GetEntry ($id)
	{
		return new PapyrineEntry ($this->database, $id);
	}

	/**
	 * Get an array of entries.
	 *
	 * @param integer $status Entry status. 0=draft, 1=live, 2=both.
	 * @param integer $limit Total number of entries to return.
	 * @param integer $frontpage Should we show non-frontpage entries.
	 * @return array
	 * @uses PapyrineEntry::TABLE
	 */
	public function GetEntries ($status, $limit = 10, $frontpage = true)
	{
		$result = sqlite_query ($this->database, sprintf (
			" SELECT id FROM %s    " .
			" WHERE blog = %s      " .
			" %s                   " .
			" ORDER BY created ASC " .
			" LIMIT %s             " ,
			PapyrineEntry::TABLE,
			$this->data["id"],
			($frontpage ? "AND onfrontpage = 1" : ""),
			($status != 2 ? "AND status = {$status}" : ""),
			$limit)
		);

		$entries = array ();
		while (sqlite_has_more ($result))
		{
			$entries[] = new PapyrineEntry ($this->database, 
			                                sqlite_fetch_single ($result));
		}

		return $entries;
	}

	/**
	 * Create a new user.
	 *
	 * @param string $nickname New user's nickname.
	 * @param string $password New user's password.
	 * @param string $firstname New user's first name.
	 * @param string $lastname New user's last name.
	 * @param string $email New user's email address.
	 * @return integer
	 * @uses PapyrineUser::Create
	 */
	public function CreateUser ($nickname, $password, $firstname, $lastname,
	                            $email)
	{
		return PapyrineUser::Create (
			$this->database, 
			$this->data["id"],
			$nickname,
			$password,
			$firstname,
			$lastname,
			$email
		);
	}

	/**
	 * Get a entry by id or username.
	 *
	 * @param integer $id Entry's unique id or username.
	 * @return PapyrineUser
	 */
	public function GetUser ($id)
	{
		return new PapyrineUser ($this->database, $id);
	}

	/**
	 * Get an array of text modifiers.
	 *
	 * @return array
	 * @uses PapyrinePlugin::TABLE
	 */
	private function GetModifiers ()
	{
		$result = sqlite_query ($this->database, sprintf (
			" SELECT id, name FROM %s " .
			" WHERE modifier = 1      " .
			" ORDER BY name ASC       " ,
			PapyrinePlugin::TABLE)
		);

		$modifiers = array ();
		while (sqlite_has_more ($result))
			$modifiers[] = sqlite_fetch_array ($result, SQLITE_ASSOC);

		return $modifiers;
	}

	/**
	 * Get an array of feed syndicators.
	 *
	 * @return array
	 * @uses PapyrinePlugin::TABLE
	 */
	private function GetSyndicators ()
	{
		$result = sqlite_query ($this->database, sprintf (
			" SELECT id FROM %s    " .
			" WHERE syndicator = 1 " .
			" ORDER BY name ASC    " ,
			PapyrinePlugin::TABLE)
		);

		$syndicators = array ();
		while (sqlite_has_more ($result))
			$syndicators[] = new PapyrinePlugin (sqlite_fetch_single ($result));

		return $syndicators;
	}

	/**
	 * Get a plugin by id.
	 *
	 * @param integer $id Plugin's unique id.
	 * @return PapyrinePlugin
	 */
	public function GetPlugin ($id)
	{
		return new PapyrinePlugin ($this->database, $id);
	}

	public function InstallPlugin ($file)
	{
		return PapyrinePluginManager::Install ($this->database, $file);
	}

	/**
	 * Turn an array of objects into an array or arrays.
	 *
	 * @param array $objects The objects to convert.
	 * @return array
	 * @uses PapyrineObject::ToArray
	 */
	public static function Objects2Array ($objects)
	{
		$output = array ();

		foreach ($objects as $object)
			$output[] = $object->ToArray();

		return $output;
	}

	public static function GenerateHTAccess ($layout)
	{
		$xml = simplexml_load_string ($layout);
		$array = Papyrine::RecurseNodes ($xml->layout);

		//write array to .htaccess
	}

	public static function RecurseNodes ($node, $string = "", $array = array ())
	{
		$string .= $node["identifier"];
		$array [] = $string . "(.?)$ Included/index.php?route=" . $node["id"];

		if (count ($node->node) == 0)
			return $array;

		foreach ($node->node as $subnode)
			Papyrine::RecurseNodes ($subnode, $string, $array);
	}

	public function GetPluginID ($object)
	{
		return PapyrinePluginManager::GetID ($this->database, $object);
	}

	public function RegisterHook ($hook, $function, $object = false)
	{
		// See if the function has already been registered for this hook.
		if (in_array ($function, $this->hooks [$hook]))
			return false;

		$this->hooks [$hook] [] = array (
			"object"   => $object,
			"function" => $function
		);
	}

	public function ExecuteHooks ($hook_id, &$params)
	{
		foreach ($this->hooks [$hook_id] as $hook)
		{
			if ($hook ["object"])
			{
				if (!$hook ["object"]->$hook ["function"] ($params))
					return false;
			}
			else
			{
				if (!$hook ["function"] ($params))
					return false;
			}
		}

		return true;
	}

	public static function GetFile ($url)
	{
		$snoopy = new Snoopy ();
		$text = $snoopy->fetch ($url);

		// create file (md5 the url for unique name?)
		// save file

		// return location
	}
}

?>
