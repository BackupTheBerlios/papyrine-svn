<?php

class PapyrinePlugin
{
	private $_xml;
	private $_path;

	function __construct( $path )
	{
		$this->_path = $path;
		$this->_xml = simplexml_load_file( $this->_path . 'plugin.xml');
	}

	function __get( $var )
	{
		return $this->_xml->$var;
	}

	function validate()
	{
		if (!($dom_sxe = dom_import_simplexml( $this->_xml )))
			return false;

		$dom = new DOMDocument( "1.0" );
		$dom_sxe = $dom->importnode( $dom_sxe, true );
		$dom_sxe = $dom->appendchild( $dom_sxe );

		return $dom->relaxNGValidate();
	}

	function loadClass()
	{
		require_once( $this->_path . $this->_xml->class . '.php' );
	}

	function getInstance()
	{
		$this->loadClass();
		$class_name = (string) $this->_xml->class;
		return new $class_name();
	}
}

?>
