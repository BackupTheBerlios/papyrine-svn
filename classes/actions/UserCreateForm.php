<?php

/**
 * Papyrine is a weblogging system built using PHP5.
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
 * @subpackage Actions
 * @author Thomas Reynolds <tdreyno@gmail.com>
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class UserCreateForm extends ActionForm 
{
	function validate (ActionMapping $map)
	{
		if (
		    !isset ($this->email)           || 
		    !isset ($this->password)        || 
		    !isset ($this->repeat_password) || 
		    !isset ($this->name))
			return false;

		if ($this->password != $this->repeat_password)
			return false;

		if (!$this->_validate_email ($this->email))
			return false;

		if (!$this->_is_unique ($this->email))
			return false;

		return true;
	}

	function setemail ($email)
	{
		$this->email = $email;
	}

	function setpassword ($password)
	{
		$this->password = $password;
	}

	function setrepeat_password ($repeat_password)
	{
		$this->repeat_password = $repeat_password;
	}

	function setname ($name)
	{
		$this->name = $name;
	}

	private function _validate_email ($email)
	{
		// Create the syntactical validation regular expression
		$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

		// Validate the syntax
		if (eregi ($regexp, $email))
		{
			list ($username, $domaintld) = split ("@", $email);

			// Validate the domain
			return (getmxrr ($domaintld, $mxrecords));
		}

		return false;
	}

	private function _is_unique ($email)
	{
		global $papyrine;

		return $papyrine->emailExists ($email);
	}
}	
?>
