<?php

namespace CouchPHP;

class ClientException extends \Exception {
	
	const DOCUMENT_NOT_FOUND = 'not_found';
	const DOCUMENT_CONFLICT = 'conflict';

}