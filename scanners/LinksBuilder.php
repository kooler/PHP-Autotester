<?php
include_once 'BaseScanner.php';
/**
 * Class for building site tree from results of recursive links scanning
 * @author kooler
 */
class LinksBuilder extends BaseScanner {
	/**
	 * Site url(for ex. http://google.com)
	 * @var string
	 */
	private $siteUrl = null;
	/**
	 * Scanning start page(for ex. http://google.com/main.php)
	 * @var string
	 */
	private $startPage = null;
	/**
	 * Constructor. Save start page.
	 * @param $startPage start page of the site(from this page scanner will start)
	 */
	function __construct($startPage) {
		$this->startPage = $startPage;
	}
	/**
	 * Start scaning from base page
	 * @see scanners/BaseScanner#start()
	 * @param $basePage start page for scanning
	 */
	function start() {
		if (!empty($this->startPage)) {
			$this->extractPageLinks($this->startPage);
		}
	}
	/**
	 * Get links from page
	 * @param $pageUrl url to the page
	 */
	private function extractPageLinks($pageUrl) {
		$content = $this->getPageContent($pageUrl);
		if ($content == -1) return;
		//Check the content of the page and add link and result to the array
		$pageStatus = $this->getParser()->check($content);
		if ($this->addLink($pageUrl, $pageStatus)) {
			preg_match_all('/<a\s[^>]*href=([\"\']??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU', $content, $pageLinks);
			$pageLinks = $pageLinks[2];
			foreach ($pageLinks as $pLink) {
				if (!empty($pLink) && (strpos($pLink, 'http://') === FALSE)) {
					$this->normalizeLink($pLink);
					if (strpos($pLink, $this->siteUrl) == 0) {
						$this->extractPageLinks($pLink);
					}
				}
			}
		}
	}
	/**
	 * Get file content
	 * @param $url url to the page
	 * @return page content(html)
	 */
	private function getPageContent($pageUrl) {
		try {
			$content = @file_get_contents($pageUrl);
			return $content;
		} catch (Exception $e) {
			return -1;
		}
	}
	/**
	 * Set site URL
	 * @param $url url of the site (ex http://google.com)
	 */
	function setSiteUrl($url) {
		$this->siteUrl = $url;
	}
	/**
	 * Normalize link - if it doesn't have domain name - add it
	 * @param link
	 * @return normalized link
	 */
	private function normalizeLink(&$link) {
		if (strpos($link, $this->siteUrl) !== 0) {
			$newLink = $this->siteUrl;
			if (substr($link, 0, 1) != '/') {
				$newLink .= '/';
			}
			$newLink .= $link;
			$link = $newLink;
		}
	}
}