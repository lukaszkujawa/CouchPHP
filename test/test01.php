<?php

require_once __DIR__ . '/../vendor/autoload.php';

new CouchPHP\Client('http://192.168.50.102:5984/', 'test');
