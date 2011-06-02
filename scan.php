<?php
function __autoload($name) {
	if (file_exists('scanners/'.$name.'.php')) {
		include_once 'scanners/'.$name.'.php';
	} elseif (file_exists('parsers/'.$name.'.php')) {
		include_once 'parsers/'.$name.'.php';
	}
}

include 'config.php';

$siteUrl = SITE_URL;

$consoleMode = !isset($_SERVER['HTTP_USER_AGENT']);

//Check script params
if ($consoleMode && !empty($argv[1])) {
	$siteUrl = $argv[1];
}

//Configuration
$scannerClass = SCANNER;
$scanner = new $scannerClass($siteUrl);
$scanner->setConsoleMode($consoleMode);
$scanner->setSiteUrl($siteUrl);
$parserClass = PARSER;
$parser = new $parserClass();
$scanner->setParser($parser);

//Start scanning
$scanner->start();

if (!$consoleMode) {
	//Output the result
	$links = $scanner->getLinks();
	if (count($links) > 0) {
		foreach ($links as $link=>$result) {
			echo '<div style="background-color: '.($result ? 'green' : 'red').';padding:5px;margin:5px">'.$link.'</div>';
		}
	}
}
