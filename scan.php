<?php
function __autoload($name) {
	if (file_exists('scanners/'.$name.'.php')) {
		include_once 'scanners/'.$name.'.php';
	} elseif (file_exists('parsers/'.$name.'.php')) {
		include_once 'parsers/'.$name.'.php';
	}
}

include 'config.php';

//Configuration
$scannerClass = SCANNER;
$scanner = new $scannerClass(SITE_URL);
$scanner->setSiteUrl(SITE_URL);
$parserClass = PARSER;
$parser = new $parserClass();
$scanner->setParser($parser);

//Start scanning
$scanner->start();

//Output the result
$links = $scanner->getLinks();
if (count($links) > 0) {
	foreach ($links as $link=>$result) {
		echo '<div style="background-color: '.($result ? 'green' : 'red').';padding:5px;margin:5px">'.$link.'</div>';
	}
}