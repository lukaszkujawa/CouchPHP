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


	public function getPath() {
		return $this->path;
	}

	public function getDbname() {
		return $this->dbname;
	}

	public function getHost() {
		return $this->host;
	}

	protected function parseResponse( $resp ) {
		if( ! $resp ) {
			throw new \Exception('Empty response from the server');
		}

		$resp = json_decode( $resp );

		if( ! $resp ) {
			throw new \Exception('Response is not a JSON');
		}		

		if( isset( $resp->error ) ) {
			throw new ClientException( $resp->error );
		}

		return $resp;
	}

	public function getAllDocs() {
		$all = $this->get( '/_all_docs');
		if( $all->total_rows == 0 ) {
			return array();
		}

		$docs = array();
		foreach( $all->rows as $row ) {
			$docs[] = new Document( $row );
		}
		return $docs;
	}

	public function getView( $designDocName, $viewName, $params = false ) {
		$url = sprintf( "_design/%s/_view/%s", $designDocName, $viewName );
		if( $params ) {
			$url .= '?';
			$url .= http_build_query( $params );
		}

		return $this->get( $url );
	}

	public function getAttachment( $id, $attachmentName ) {
		$uri = urlencode( $id );
		$uri .=  '/' . $attachmentName;
		 
		$data = CURL::get( $this->getUrl( $uri ) );

		return $data;
	}

	public function getUrl( $uri ) {
		return sprintf( '%s%s', $this->path, $uri );
	}

	public function get( $uri ) {
		return $this->parseResponse( CURL::get( $this->getUrl( $uri ) ) );
	}

	public function post( $uri, $data = array() ) {
		return $this->parseResponse( CURL::post( $this->getUrl( $uri ), json_encode( $data ) ) );
	}

	public function put( $uri, $data = array() ) {
		return $this->parseResponse( CURL::put( $this->getUrl( $uri ), json_encode( $data ) ) );
	}

}