<?php
/**
 * Class for parsing page content
 * Each class should have Check method that returns the result of checking
 * @author kooler
 */
abstract class BaseParser {
	/**
	 * Method for checking content for matching to patterm
	 * @param content(html)
	 * @return true|false result
	 */
	abstract function check($content);	
}