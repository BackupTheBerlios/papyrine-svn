<?php

class PapyrinePlugin
{
	private $_xml;

	function __construct( $xml_path )
	{
		if( file_exists( $xml_path ) ) {
			$this->_xml = simplexml_load_file( $xml_path );
		} else {
			throw new FileNotFoundException();
		}
	}

	function __get( $var )
	{
		return $this->_xml->$var;
	}
}

?>
