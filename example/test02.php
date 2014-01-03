<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new CouchPHP\Client('http://127.0.0.1:5984/', 'test');
$ret = $client->truncate();
if( ! isset( $ret->error ) && $ret->ok == 1 ) {
	echo "Database truncated\n";
}

$ret = CouchPHP\Client::createDatabase( 'http://127.0.0.1:5984/', 'test' );
if( isset( $ret->error ) && $ret->error = 'file_exists' ) {
	echo "Database already exist\n";
}