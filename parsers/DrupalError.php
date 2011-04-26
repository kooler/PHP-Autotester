<?php
include_once 'BaseParser.php';
/**
 * Check site for Drupal error messages
 * @author kooler
 */
class DrupalError extends BaseParser {
	/**
	 * Error message pattern
	 * @var string
	 */
	private $PATTERN = '/<div class=\"messages error\">(.*)<\/div>/siU';
	/**
	 * Check method
	 * @see parsers/BaseParser#check($content)
	 */
	function check($content) {
		preg_match_all($this->PATTERN, $content, $result);
		return (count($result[0]) == 0);
	}
}