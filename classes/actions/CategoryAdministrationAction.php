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

class CategoryAdministrationAction extends Action 
{	
	function execute (ActionMapping $map, ActionForm $form, Request $req)
	{
		global $papyrine;

		$papyrine->categories =& $papyrine->getCategories();

		header("Content-Type: application/xhtml+xml;charset=UTF-8");
		$papyrine->display ('admin/header.html');
		$papyrine->display ($map->getParameter());
		$papyrine->display ('admin/footer.html');
	}
}	
?>
