<?php

namespace CouchPHP;

class Client {
	
	static private $lastInstance;

	protected $host;
	protected $dbname;
	protected $path;

	public function __construct($host, $dbname) {
		$this->host = $host;
		$this->dbname = $dbname;

		$this->path = sprintf( $host . '/' . $dbname . '/' );

		self::$lastInstance = $this;
	}

	static public function getLastInstance() {
		return self::$lastInstance;
	}


	public function parseResponse( $resp ) {
		$resp = json_decode( $resp );
		
		if( ! $resp ) {
			throw new \Exception('Empty response from the server');
		}

		if( isset( $resp->error ) ) {
			throw new ClientException( $resp->error );
		}

		return $resp;
	}

	public function getUrl( $uri ) {
		return sprintf( '%s%s', $this->path, $uri );
	}

	public function get( $uri ) {
		return $this->parseResponse( CURL::get( $this->getUrl( $uri ) ) );
	}

	public function post( $uri, $data ) {
		return $this->parseResponse( CURL::post( $this->getUrl( $uri ), json_encode( $data ) ) );
	}

	public function put( $uri, $data ) {
		return $this->parseResponse( CURL::put( $this->getUrl( $uri ), json_encode( $data ) ) );
	}

}