<?php

/**
* Base plugin class.
*/

require_once 'Savant2/Plugin.php';


/**
* 
* Creates HTML forms with CSS and table-based layouts.
* 
* $Id: Savant2_Plugin_form.php,v 1.21 2004/08/02 00:39:40 pmjones Exp $
* 
* @author Paul M. Jones <pmjones@ciaweb.net>
* 
* @package Savant2
* 
* @todo Add valid-flag and valid-message for elements
* 
* @todo Add non-standard elements: date, time, hierselect, autocomplete
* 
* @license http://www.gnu.org/copyleft/lesser.html LGPL
* 
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as
* published by the Free Software Foundation; either version 2.1 of the
* License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
*/

class Savant2_Plugin_form extends Savant2_Plugin {
	
	
	/**
	* 
	* The CSS class to use when generating form layout.
	* 
	* This class name will be applied to the following tags:
	* 
	* - div
	* - fieldset
	* - legend
	* - table
	* - tr
	* - th
	* - td
	* - label
	* 
	* @access public
	* 
	* @var array
	* 
	*/
	
	var $class = '';
	
	
	/**
	* 
	* The default 'float' style for fieldset blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $float = 'left';
	
	
	/**
	* 
	* The default 'clear' style for fieldset blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $clear = 'both';
	
	
	/**
	* 
	* The sprintf() format for element notes in col-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $noteCol = '<span style="font-size: 80%%; font-style: italic;">%s</span>';
	
	
	/**
	* 
	* The sprintf() format for element notes in row-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $noteRow = '<span style="font-size: 80%%; font-style: italic;">%s</span>';
	
	
	/**
	* 
	* The text used to separate radio buttons in col-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $radioCol = '<br />';
	
	
	/**
	* 
	* The text used to separate radio buttons in row-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $radioRow = '&nbsp;&nbsp;';
	
	
	/**
	* 
	* The base number of tabs to use when tidying up the generated HTML.
	* 
	* @access public
	* 
	* @var int
	* 
	*/
	
	var $tabBase = 2;
	
	
	/**
	* 
	* The sprintf() format for validation messages in col-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $validCol = '<br /><span style="color: red; font-size: 80%%;">%s</span>';
	
	
	/**
	* 
	* The sprintf() format for validation messages in col-type blocks.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $validRow = '<br /><span style="color: red; font-size: 80%%;">%s</span>';
	
	
	/**
	* 
	* Whether or not to automatically dispel magic quotes from values.
	* 
	* @access public
	* 
	* @var bool
	* 
	*/
	
	var $unquote = true;
	
	
	/**
	* 
	* The kind of fieldset block being generated ('col' or 'row').
	* 
	* @access public
	* 
	* @var bool
	* 
	*/
	
	var $_blockType = null;
	
	
	/**
	* 
	* The legend for the fieldset block, if any.
	* 
	* @access public
	* 
	* @var string
	* 
	*/
	
	var $_blockLabel = null;
	
	
	/**
	* 
	* Whether or not the form is generating elements within a fieldset block.
	* 
	* @access public
	* 
	* @var bool
	* 
	*/
	
	var $_inBlock = false;
	
	
	/**
	* 
	* Whether or not the form is generating elements as a group.
	* 
	* @access public
	* 
	* @var bool
	* 
	*/
	
	var $_inGroup = false;
	
	
	/**
	* 
	* The number of tabs to use before certain tags when tidying HTML.
	* 
	* @access public
	* 
	* @var bool
	* 
	*/
	
	var $_tabs = array(
		'form'                  => 0,
		'/form'                 => 0,
		'div'                   => 1,
		'/div'                  => 1,
		'fieldset'              => 1,
		'/fieldset'             => 1,
		'legend'                => 2,
		'table'                 => 2,
		'/table'                => 2,
		'tr'                    => 3,
		'/tr'                   => 3,
		'th'                    => 4,
		'/th'                   => 4,
		'td'                    => 4,
		'/td'                   => 4,
		'label'                 => 5,
		'input type="button"'   => 5,
		'input type="checkbox"' => 5,
		'input type="file"'     => 5,
		'input type="hidden"'   => 5,
		'input type="image"'    => 5,
		'input type="password"' => 5,
		'input type="reset"'    => 5,
		'input type="submit"'   => 5,
		'input type="text"'     => 5,
		'textarea'              => 5,
		'select'                => 5,
		'/select'               => 5,
		'option'                => 6
	);
	
		
	/**
	* 
	* Central switcher API for the the various public methods.
	* 
	* @access public
	* 
	* @param string $method The public method to call from this class; all
	* additional parameters will be passed to the called method, and all
	* returns from the mehtod will be tidied.
	* 
	* @return string HTML generated by the public method.
	* 
	*/
	
	function plugin($method)
	{
		// only pass calls to public methods (i.e., no leading underscore)
		if (substr($method, 0, 1) != '_' && method_exists($this, $method)) {
			
			// get all arguments and drop the first one (the method name)
			$args = func_get_args();
			array_shift($args);
			
			// call the method, then return the tidied-up HTML results
			$html = call_user_func_array(array(&$this, $method), $args);
			return $this->_tidy($html);
		}
	}
	
	
	/**
	* 
	* Sets the value of a public property.
	* 
	* @access public
	* 
	* @param string $key The name of the property to set.
	* 
	* @param mixed $val The new value for the property.
	* 
	* @return void
	* 
	*/
	
	function set($key, $val)
	{
		if (substr($key, 0, 1) != '_' && isset($this->$key)) {
			$this->$key = $val;
		}
	}
	
	
	// ---------------------------------------------------------------------
	//
	// Form methods
	//
	// ---------------------------------------------------------------------
	
	
	/**
	* 
	* Starts the form.
	* 
	* The form defaults to 'action="$_SERVER['REQUEST_URI']"' and
	* 'method="post"', but you can override those, and add any other
	* attributes you like.
	* 
	* @access public
	* 
	* @param array|string $attr Attributes to add to the form tag.
	* 
	* @return A <form> tag.
	* 
	*/
	
	function start($attr = null)
	{
		// make sure there is at least an empty array of attributes
		if (is_null($attr)) {
			$attr = array();
		}
		
		// make sure there is a default action and method from
		// the attribute array.
		if (is_array($attr)) {
			
			// default action
			if (! isset($attr['action'])) {
				$attr['action'] = $_SERVER['REQUEST_URI'];
			}
			
			// default method
			if (! isset($attr['method'])) {
				$attr['method'] = 'post';
			}
			
			// default encoding
			if (! isset($attr['enctype'])) {
				$attr['enctype'] = 'multipart/form-data';
			}
		}
		
		// start the form
		$html = '<form';
		$html .= $this->_attr($attr) . ">";
		return $html;
	}
	
	
	/**
	* 
	* Ends the form and closes any existing layout.
	* 
	* @access public
	* 
	* @return The ending layout HTML and a </form> tag.
	* 
	*/
	
	function end()
	{
		$html = '';
		$html .= $this->group('end');
		$html .= $this->block('end');
		return $html . '</form>';
	}
	
	
	// ---------------------------------------------------------------------
	//
	// Element methods
	//
	// ---------------------------------------------------------------------
	
	
	/**
	* 
	* Generates a 'button' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function button($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$html =  $this->_input('button', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'checkbox' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function checkbox($name, $value = null, $label = null, $options = null,
		$attr = null, $validCode = null, $validMsg = null)
	{
		if (is_null($options)) {
			$options = array(1, 0);
		} else {
			settype($options, 'array');
		}
		
		$options = $this->_unquote($options);
		
		if (isset($options[1])) {
			$html = $this->_input('hidden', $name, $options[1]);
		} else {
			$html = '';
		}
		
		$html .= '<input type="checkbox"';
		$html .= ' name="' . htmlspecialchars($name) . '"';
		$html .= ' value="' . htmlspecialchars($options[0]) . '"';
		
		if ($value == $options[0]) {
			$html .= ' checked="checked"';
		}
		
		$html .= $this->_attr($attr);
		$html .= ' />';
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'file' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function file($name, $value, $label = null, $attr = null, $validCode = null,
		$validMsg = null)
	{
		$html = $this->_input('file', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'hidden' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function hidden($name, $value, $label = null, $attr = null, $validCode = null,
		$validMsg = null)
	{
		$html = $this->_input('hidden', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates an 'image' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $src The image HREF source.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function image($name, $src, $label = null, $attr = null, $validCode = null,
		$validMsg = null)
	{
		$html = '<input type="image"';
		$html .= ' name="' . htmlspecialchars($name) . '"';
		$html .= ' src="' . htmlspecialchars($src) . '"';
		$html .= $this->_attr($attr);
		$html .= ' />';
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'password' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function password($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$html = $this->_input('password', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a set of radio button elements.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The radio value to mark as 'checked'.
	* 
	* @param string $label The element label.
	* 
	* @param array $options An array of key-value pairs where the array
	* key is the radio value, and the array value is the radio text.
	* 
	* @param array|string Attributes added to each radio.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The radio buttons HTML.
	* 
	*/
	
	function radio($name, $value = null, $label = null, $options = null,
		$attr = null, $validCode = null, $validMsg = null)
	{
		settype($options, 'array');
		$value = $this->_unquote($value);
		
		$list = array();
		foreach ($options as $optval => $optlabel) {
			$radio = '<label style="white-space: nowrap;"><input type="radio"';
			$radio .= ' name="' . htmlspecialchars($name) . '"';
			$radio .= ' value="' . htmlspecialchars($optval) . '"';
			
			if ($optval == $value) {
				$radio .= ' checked="checked"';
			}
			
			$radio .= ' />' . htmlspecialchars($optlabel) . '</label>';
			$list[] = $radio;
			
		}
		
		// pick the separator string
		if ($this->_inBlock && $this->_blockType == 'row') {
			$sep = $this->radioRow;
		} else {
			$sep = $this->radioCol;
		}
		
		// done!
		$html = implode($sep, $list);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'reset' button.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function reset($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$html =  $this->_input('reset', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates 'select' list of options.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The option value to mark as 'selected'; if an 
	* array, will mark all values in the array as 'selected' (used for
	* multiple-select elements).
	* 
	* @param string $label The element label.
	* 
	* @param array $options An array of key-value pairs where the array
	* key is the radio value, and the array value is the radio text.
	* 
	* @param array|string Attributes added to the 'select' tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The select tag and options HTML.
	* 
	*/
	
	function select($name, $value = null, $label = null, $options = null,
		$attr = null, $validCode = null, $validMsg = null)
	{
		settype($value, 'array');
		settype($options, 'array');
		
		$value = $this->_unquote($value);
		
		$html = '';
		$html .= '<select name="' . htmlspecialchars($name) . '"';
		$html .= $this->_attr($attr);
		$html .= '>';
		
		$list = array();
		foreach ($options as $optval => $optlabel) {
			$opt = '<option value="' . htmlspecialchars($optval) . '"';
			$opt .= ' label="' . htmlspecialchars($optlabel) . '"';
			if (in_array($optval, $value)) {
				$opt .= ' selected="selected"';
			}
			$opt .= '>' . htmlspecialchars($optlabel) . "</option>";
			$list[] = $opt;
		}
		
		$html .= implode('', $list);
		$html .= '</select>';
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'submit' button.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function submit($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$html =  $this->_input('submit', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Adds a note to the form.
	* 
	* @access public
	* 
	* @param string $text The note text.
	* 
	* @param string $label The label, if any, for the note.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function note($text, $label = null, $validCode = null, $validMsg = null)
	{
		// pick the format
		if ($this->_inBlock && $this->_blockType == 'row') {
			$format = $this->noteRow;
		} else {
			$format = $this->noteCol;
		}
		
		// don't show the format when there's no note
		if (trim($text) == '') {
			$html = '';
		} else {
			$html = sprintf($format, $text);
		}
		
		// format and return
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'text' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function text($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$html = $this->_input('text', $name, $value, $attr);
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	/**
	* 
	* Generates a 'textarea' element.
	* 
	* @access public
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param string $label The element label.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element HTML.
	* 
	*/
	
	function textarea($name, $value = null, $label = null, $attr = null,
		$validCode = null, $validMsg = null)
	{
		$value = $this->_unquote($value);
		$html = '';
		$html .= '<textarea name="' . htmlspecialchars($name) . '"';
		$html .= $this->_attr($attr);
		$html .= '>' . htmlspecialchars($value) . '</textarea>';
		return $this->_element($label, $html, $validCode, $validMsg);
	}
	
	
	// ---------------------------------------------------------------------
	//
	// Layout methods
	//
	// ---------------------------------------------------------------------
	
	
	/**
	* 
	* Builds HTML to start, end, or split layout blocks.
	* 
	* @param string $action Whether to 'start', 'split', or 'end' a block.
	* 
	* @param string $label The fieldset legend.  If an empty string,
	* builds a fieldset with no legend; if null, builds a div (not a
	* fieldset).
	* 
	* @param string $type The layout type to use, 'col' or 'row'.  The
	* 'col' layout uses a left-column for element labels and a
	* right-column for the elements; the 'row' layout shows the elements
	* left-to-right, with the element label over the element, all in a
	* single row.
	* 
	* @param string $float Whether the block should float 'left' or
	* 'right' (set to an empty string if you don't want floating). 
	* Defaults to the value of $this->float.
	* 
	* @param string $float Whether the block should be cleared of 'left'
	* or 'right' floating blocks (set to an empty string if you don't
	* want to clear).  Defaults to the value of $this->clear.
	* 
	* @return string The appropriate HTML for the block action.
	* 
	*/
	
	function block($action = 'start', $label = null, $type = 'col', 
		$float = null, $clear = null)
	{
		if (is_null($float)) {
			$float = $this->float;
		}
		
		if (is_null($clear)) {
			$clear = $this->clear;
		}
		
		switch (strtolower($action)) {
		
		case 'start':
			return $this->_blockStart($label, $type, $float, $clear);
			break;
		
		case 'split':
			return $this->_blockSplit();
			break;
		
		case 'end':
			return $this->_blockEnd();
			break;
		
		}
		
		return;
	}
	
	/**
	* 
	* Builds the layout for a group of elements; auto-starts a block if needed.
	* 
	* @access public
	* 
	* @param string $type Whether to 'start' or 'end' the group.
	* 
	* @param string $label The label for the group.
	* 
	* @return string The element-group layout HTML.
	* 
	*/
	
	function group($type, $label = null)
	{
		$html = '';
		
		// if not in a block, start one
		if (! $this->_inBlock) {
			$html .= $this->block();
		}
		
		// are we starting a new group?
		if ($type == 'start' && ! $this->_inGroup) {
			
			// build a 'col' group?
			if ($this->_blockType == 'col') {
				$html .= $this->_tag('tr');
				$html .= $this->_tag('th');
				
				// add a label if specified
				if (! is_null($label)) {
					$html .= $this->_tag('label');
					$html .= htmlspecialchars($label);
					$html .= '</label>';
				}
				$html .= '</th>';
				$html .= $this->_tag('td');
			}
		
			// build a 'row' group?
			if ($this->_blockType == 'row') {
				$html .= $this->_tag('td');
				if (! is_null($label)) {
					$html .= $this->_tag('label');
					$html .= htmlspecialchars($label);
					$html .= '</label><br />';
				}
			}
			
			// we're in a group now
			$this->_inGroup = true;
			
		}
		
		// are we ending a current group?
		if ($type == 'end' && $this->_inGroup) {
			
			// we're out of the group now
			$this->_inGroup = false;
			
			if ($this->_blockType == 'col') {
				$html .= '</td></tr>';
			}
			
			if ($this->_blockType == 'row') {
				$html .= '</td>';
			}
		}
		
		// done!
		return $html;
	}
	
	
	// ---------------------------------------------------------------------
	//
	// Private support methods
	//
	// ---------------------------------------------------------------------
	
	
	/**
	* 
	* Builds an attribute string for a tag.
	* 
	* @access private
	* 
	* @param array|string $attr The attributes to add to a tag; if an array,
	* the key is the attribute name and the value is the attribute value; if a
	* string, adds the literal string to the tag.
	* 
	* @return string A string of tag attributes.
	* 
	*/
	
	function _attr($attr = null)
	{
		if (is_array($attr)) {
			// add from array
			$html = '';
			foreach ($attr as $key => $val) {
				$key = htmlspecialchars($key);
				$val = htmlspecialchars($val);
				$html .= " $key=\"$val\"";
			}
		} elseif (! is_null($attr)) {
			// add from scalar
			$html = " $attr";
		} else {
			$html = null;
		}
		
		return $html;
	}
	
	
	/**
	* 
	* Builds an HTML opening tag with class and attributes.
	* 
	* @access private
	* 
	* @param string $type The tag type ('td', 'th', 'div', etc).
	* 
	* @param array|string $attr Additional attributes for the tag.
	* 
	* @return string The opening tag HTML.
	* 
	*/
	
	function _tag($type, $attr = null)
	{
		// open the tag
		$html = '<' . $type;
		
		// add a CSS class attribute
		if ($this->class) {
			$html .= ' class="' . $this->class . '"';
		}
		
		// add other attributes
		$html .= $this->_attr($attr);
		
		// done!
		return $html . ">";
	}
	
	
	/**
	* 
	* Adds an element to the table layout; auto-starts a block as needed.
	* 
	* @access private
	* 
	* @param string $label The label for the element.
	* 
	* @param string $fieldHtml The HTML for the element field.
	* 
	* @param mixed $validCode A validation code.  If exactly boolean
	* true, or exactly null, no validation message will be displayed. 
	* If any other integer, string, or array value, the element is
	* treated as not-valid and will display the corresponding message.
	* 
	* @param mixed array|string A validation message.  If an array, the
	* $validCode value is used as a key for this array to determine
	* which message(s) should be displayed.
	* 
	* @return string The element layout HTML.
	* 
	*/
	
	function _element($label, $fieldHtml, $validCode = null, $validMsg = null)
	{
		$html = '';
		
		// if we're starting an element without having started
		// a block first, forcibly start a default block
		if (! $this->_inBlock) {
		
			// is there a label for the element?
			if (is_null($label)) {
				// not in a block, and no label specified. this is most
				// likely a hidden element above the form itself. just
				// return the HTML as it is, no layout at all.
				return $fieldHtml;
			} else {
				// start a block and continue
				$html .= $this->block();
			}
		}
		
		// are we checking validation and adding validation messages?
		if ($validCode === null || $validCode === true) {
		
			// do nothing
			
		} else {
		
			// force to arrays so we can have multiple messages.
			settype($validCode, 'array');
			settype($validMsg, 'array');
			
			// pick the format
			if ($this->_inBlock && $this->_blockType == 'row') {
				$format = $this->validRow;
			} else {
				$format = $this->validCol;
			}
			
			// add the validation messages
			foreach ($validCode as $code) {
				if (isset($validMsg[$code])) {
					// print the message
					$fieldHtml .= sprintf(
						$format,
						$validMsg[$code]
					);
				} else {
					// print the code
					$fieldHtml .= sprintf(
						$format,
						$code
					);
				}
			}
		}
		
		// are we in a group?
		if (! $this->_inGroup) {
			// no, put the element in a group by itself
			$html .= $this->group('start', $label);
			$html .= $fieldHtml;
			$html .= $this->group('end');
		} else {
			// yes, just add the element to the current group.
			// elements in groups do not get their own labels,
			// the group has already set the label.
			$html .= $fieldHtml;
		}
		
		// done!
		return $html;
	}
	
	
	/**
	* 
	* Recursively removes magic quotes from values and arrays.
	* 
	* @access private
	* 
	* @param mixed $value The value from which to remove magic quotes.
	* 
	* @return mixed The un-quoted value.
	* 
	*/
	
	function _unquote($value)
	{
		if (! $this->unquote) {
			return $value;
		}
		
		static $mq;
		if (! isset($mq)) {
			$mq = get_magic_quotes_gpc();
		}
		
		if ($mq) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$value[$k] = $this->_unquote($v);
				}
			} else {
				$value = stripslashes($value);
			}
		}
		
		return $value;
	}
	
	
	/**
	* 
	* Builds an 'input' element.
	* 
	* @access private
	* 
	* @param string $type The input type ('text', 'hidden', etc).
	* 
	* @param string $name The element name.
	* 
	* @param mixed $value The element value.
	* 
	* @param array|string Attributes for the element tag.
	* 
	* @return The 'input' tag HTML.
	* 
	*/
	
	function _input($type, $name, $value = null, $attr = null)
	{
		$value = $this->_unquote($value);
		$html = '<input type="' . $type . '"';
		$html .= ' name="' . htmlspecialchars($name) . '"';
		$html .= ' value="' . htmlspecialchars($value) . '"';
		$html .= $this->_attr($attr);
		$html .= ' />';
		return $html;
	}
	
	
	/**
	* 
	* Puts in newlines and tabs to make the source code readable.
	* 
	* @access private
	* 
	* @param string $html The HTML to tidy up.
	* 
	* @return string The tidied HTML.
	* 
	*/
	
	function _tidy($html)
	{
		foreach ($this->_tabs as $key => $val) {
			$key = '<' . $key;
			$pad = str_pad('', $val + $this->tabBase, "\t");
			$html = str_replace($key, "\n$pad$key", $html);
		}
		
		return $html;
	}
	
	
	/**
	* 
	* Generates HTML to start a fieldset block.
	* 
	* @access private
	* 
	* @param string $label The fieldset legend.  If an empty string,
	* builds a fieldset with no legend; if null, builds a div (not a
	* fieldset).
	* 
	* @param string $type The layout type to use, 'col' or 'row'.  The
	* 'col' layout uses a left-column for element labels and a
	* right-column for the elements; the 'row' layout shows the elements
	* left-to-right, with the element label over the element, all in a
	* single row.
	* 
	* @param string $float Whether the block should float 'left' or
	* 'right' (set to an empty string if you don't want floating). 
	* Defaults to the value of $this->float.
	* 
	* @param string $float Whether the block should be cleared of 'left'
	* or 'right' floating blocks (set to an empty string if you don't
	* want to clear).  Defaults to the value of $this->clear.
	* 
	* @return string The HTML to start a block.
	* 
	*/
	
	function _blockStart($label = null, $type = 'col', $float = null,
		$clear = null)
	{
		$html = '';
		// are we already in a block? if so, end the current one
		// so we can start a new one.
		if ($this->_inBlock) {
			$html .= $this->block('end');
		}
		
		// set the new block type and label
		$this->_inBlock = true;
		$this->_blockType = $type;
		$this->_blockLabel = $label;
		
		// build up the "style" attribute for the new block
		$style = '';
		
		if ($float) {
			$style .= " float: $float;";
		}
		
		if ($clear) {
			$style .= " clear: $clear;";
		}
		
		if (! empty($style)) {
			$attr = 'style="' . trim($style) . '"';
		} else {
			$attr = null;
		}
		
		// build the block opening HTML itself; use a fieldset when a label
		// is specifed, or a div when the label is not specified
		if (is_string($this->_blockLabel)) {
		
			// has a label, use a fieldset with e style attribute
			$html .=  $this->_tag('fieldset', $attr);
			
			// add the label as a legend, if it exists
			if (! empty($this->_blockLabel)) {
				$html .=  $this->_tag('legend');
				$html .= htmlspecialchars($this->_blockLabel);
				$html .= '</legend>';
			}
			
		} else {
			// no label, use a div with the style attribute
			$html .= $this->_tag('div', $attr);
		}
		
		// start a table for the block elements
		$html .=  $this->_tag('table');
		
		// if the block is row-based, start a row
		if ($this->_blockType == 'row') {
			$html .=  $this->_tag('tr');
		}
		
		// done!
		return $html;
	}
	
	
	/**
	* 
	* Generates the HTML to end a block.
	* 
	* @access public
	* 
	* @return string The HTML to end a block.
	* 
	*/
	
	function _blockEnd()
	{
		// if not in a block, return right away
		if (! $this->_inBlock) {
			return;
		}
		
		$html = '';
		
		// are we in a group?  if so, end it.
		if ($this->_inGroup) {
			$html .= $this->group('end');
		}
		
		// end the block layout proper
		if ($this->_blockType == 'row') {
			// previous block was type 'row'
			$html .=  '</tr></table>';
		} else {
			// previous block was type 'col'
			$html .=  '</table>';
		}
		
		// end the fieldset or div tag for the block
		if (is_string($this->_blockLabel)) {
			// there was a label, so the block used fieldset
			$html .=  '</fieldset>';
		} else {
			// there was no label, so the block used div
			$html .=  '</div>';
		}
		
		// reset tracking properties
		$this->_inBlock = false;
		$this->_blockType = null;
		$this->_blockLabel = null;
		
		// done!
		return $html;
	}
	
	
	/**
	* 
	* Generates the layout to split the layout within a block.
	* 
	* @access public
	* 
	* @return string The HTML to split the layout with in a block.
	* 
	*/
	
	function _blockSplit()
	{
		if (! $this->_inBlock) {
			return;
		}
		
		$html = '';
		
		if ($this->_inGroup) {
			$html .= $this->group('end');
		}
		
		if ($this->_blockType == 'row') {
			$html .= '</tr>';
			$html .= $this->_tag('tr');
		}
		
		if ($this->_blockType == 'col') {
			$html .= '</table>';
			$html .= $this->_tag('table');
		}
		
		return $html;
	}
}

?>