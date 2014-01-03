<?php

require_once __DIR__ . '/../vendor/autoload.php';

CouchPHP\Client::createDatabase( 'http://127.0.0.1:5984/', 'test' );

$client = new CouchPHP\Client('http://127.0.0.1:5984/', 'test');

for( $x = 0 ; $x < 10 ; $x++ ) {
	$doc  = new CouchPHP\Document(array(
		'id' => time() . rand( 100000, 999999 ),
		'date' => date( DATE_ISO8601 )
	));

	$doc->insert();
}
