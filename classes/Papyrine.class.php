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
 * The central Papyrine object, hands database initialization.
 *
 * @author Thomas Reynolds <thomasr@infograph.com>
 * @package Papyrine
 * @subpackage Classes
 */
final class Papyrine
{
	/**
	 * An array of registered hooks.
	 *
	 * @var array 
	 */
	private $_hooks = array ();
	private $_smarty = false;

	public $database;

	function __construct ()
	{
		// Choose DB from config
		$this->database =& new SQLiteDatabasePlugin;
	}

	public static function getFile ($file)
	{
		return "/var/www/localhost/htdocs/papyrine/data/" . $file;
	}

	public function RegisterHook ($hook, $function, $object = false)
	{
		echo "Registering a hook for &quot;{$hook}&quot;<br /> ";

		// See if the function has already been registered for this hook.
		if (@in_array ($function, $this->_hooks [$hook]))
			return false;

		$this->_hooks [$hook] [] = array (
			"object"   => $object,
			"function" => $function
		);
	}

	public function ExecuteHooks ($function, $params, &$output)
	{
		foreach ($this->_hooks [$function] as $hook)
		{
			echo "Executing Hook ";
			if ($hook ["object"] != false)
			{
				echo "with Object <br />";
				if (!$hook ["object"]->$hook ["function"] ($params, $output))
					return false;
			}
			else
			{
				echo "without Object <br />";
				if (!$hook ["function"] ($params, $output))
					return false;
			}
		}

		return true;
	}

	/**
	 * If we are going to use smarty, set it up.
	 *
	 * @uses PapyrinePlugins::GetSmartyLocations
	 * @uses PapyrinePlugins::GetTemplateLocations
	 */
	private function _InitializeSmarty ()
	{
		$this->_smarty = new Smarty;

		// Make loaded plugins visible.
		$this->_smarty->plugins_dir  = array (
			"/var/www/localhost/htdocs/papyrine/libraries/smarty_plugins/", 
			"/var/www/localhost/htdocs/papyrine/libraries/smarty/core/", 
			"/var/www/localhost/htdocs/papyrine/libraries/smarty/plugins/"
		);

		// Make loaded plugins visible.
		$this->_smarty->template_dir  = "/var/www/localhost/htdocs/papyrine/templates/";
		$this->_smarty->compile_dir  = "/var/www/localhost/htdocs/papyrine/data/compiled_templates/";
		$this->_smarty->cache_dir    = "/var/www/localhost/htdocs/papyrine/data/smarty_cache/";
		$this->_smarty->assign ('database', $this->database);
		$this->_smarty->assign ('system', array (
			'url'     => '',
			'name'    => 'Papyrine',
			'version' => 0.1)
		);
	}

	public function display ($template)
	{		
		if (!$this->_smarty)
			$this->_InitializeSmarty ();

		$this->_smarty->display ($template);
	}

	/**
	 * Create a new blog.
	 *
	 * @param string $title The blog's title.
	 * @return integer
	 * @uses PapyrineBlog::Create
	 */
	public function CreateBlog ($title)
	{
		return $this->database->CreateBlog ($title);
	}

	public function CreateUser ($password, $firstname, $lastname, $email)
	{
		return $this->database->CreateUser ($password, $firstname, $lastname, 
		                                    $email);
	}

	public function GetUser ($id)
	{
		return $this->database->GetUser ($id);
	}

	public function GetUsers ($as_array = false)
	{
		return $this->database->GetUsers ($as_array);
	}

	public function GetBlog ($id)
	{
		return $this->database->GetBlog ($id);
	}

	/**
	 * Get an array of entries.
	 *
	 * @param integer $status Entry status. 0=draft, 1=live, 2=both.
	 * @param integer $limit Total number of entries to return.
	 * @param integer $frontpage Should we show non-frontpage entries.
	 * @return array
	 * @uses PapyrineEntry::TABLE
	 *
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

/*
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
*/
}

?>
