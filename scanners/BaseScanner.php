<?php
define('CACHE_FILE', 'cache/sitemap.dat');
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
	 * Output result like in console
	 */
	protected $consoleMode = false;
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
	 * Check whether link was scannes
	 * @param $link link to check
	 */
	function linkWasScanned($link) {
		return (array_search($link, $this->_linksList) !== FALSE);
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
	/**
	 * Save links to the cache
	 */
	function cacheLinks() {
		file_put_contents(CACHE_FILE, serialize($this->links));
	}
	/**
	 * Check whether links are in cache
	 */
	function isCached() {
		return file_exists(CACHE_FILE);
	}
	/**
	 * Load links from cache
	 */
	abstract function loadFromCache();
	/**
	 * Set console mode
	 * @param boolean value
	 */
	function setConsoleMode($mode) {
		$this->consoleMode = $mode;
	}
	/**
	 * Check whether remote url exists
	 */
	function urlExists($url) {
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    // don't download content
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    return curl_exec($ch);
	}
}