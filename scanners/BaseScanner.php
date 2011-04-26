<?php
/**
 * Base scanner class
 * Scanner classes are used for building site tree(list of all site pages)
 * Each scanner class should have his own Start method
 * @author kooler
 */
abstract class BaseScanner {
	protected $parser = null;
	protected $links = array();
	protected $_linksList = array();
	protected $result = array();
	/**
	 * Start the scanning process
	 */
	abstract function start();
	/**
	 * Get the result of the scanning
	 * @return array with scanning result
	 */
	function getResult() {
		return $this->links;
	}
	/**
	 * Add link to the array
	 * @param $link 	link to the page
	 * @param $result 	check result
	 */
	function addLink($link, $result) {
		if (array_search($link, $this->_linksList) === FALSE) {
			$this->links[$link] = $result;
			$this->_linksList[] = $link;
			return true;
		}
		return false;
	}
	/**
	 * Set parser
	 * @param $parser should be extended from BaseParser
	 */
	function setParser(BaseParser $parser) {
		$this->parser = $parser;
	}
	/**
	 * Get parser
	 * @result parser instance
	 */
	function getParser() {
		return $this->parser;
	}
	/**
	 * Get list of links
	 * @return array of links and their statuses
	 */
	function getLinks() {
		return $this->links;
	}
}