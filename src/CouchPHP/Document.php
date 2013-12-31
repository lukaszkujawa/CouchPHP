<?php

namespace CouchPHP;

class Document {

	private $_client;
	private $_fields;

	final public function __construct( $fields = array(), Client $client = null ) {
		$this->setClient( $client );
		$this->setFields( $fields );
	}

	static public function getById( $id, $client = null ) {
		$doc = new self( array('id' => $id), $client );
		$doc->refresh();
		return $doc;
	}

	public function __set( $name, $value ) {
		$this->_fields->$name = $value;
	}

	public function __get( $name ) {
		if( isset( $this->_fields->$name ) ) {
			return $this->_fields->$name;
		}

		return null;
	}

	public function refresh() {
		try {
			$ret = $this->_client->get( $this->getId() );
			$this->setFields( $ret );
		}catch( ClientException $e ) {
			if( $e->getMessage() == ClientException::DOCUMENT_NOT_FOUND ) {
				return false;
			}
			else {
				throw $e;
			}
		}
	}

	public function getId() {
		if( isset( $this->_fields->id ) ) {
			return $this->_fields->id;
		}
		else if( isset( $this->_fields->_id ) ) {
			return $this->_fields->_id;
		}

		return false;
	}

	protected function setFields( $fields ) {
		if( isset( $fields->id ) ) {
			$fields->_id = $fields->id;
			unset( $fields->id );
		}

		$this->_fields = (object) $fields;
	}

	protected function parseResponse( $response ) {
		if( ! $response ) {
			throw new \Exception( 'Empty response from the server' );
		}

		return $response;
	}

	public function getFields() {
		return $this->_fields;
	}

	private function setClient( $client ) {
		if( $client == null ) {
			$client = Client::getLastInstance();
		}

		$this->_client = $client;
	}

	public function getClient() {
		return $this->_client;
	}

	public function insert() {
		if( $this->getId() ) {
			$ret = $this->_client->put( $this->getId(), $this->getFields() );
		}
		else {
			$ret = $this->_client->post( '', $this->getFields() );
		}
	}

	public function insertOverwrite() {
		for( $i = 100 ; $i > 0 ; $i-- ) {
			try {
				$this->insert();
				return true;
			}
			catch( ClientException $e ) {
				if( $e->getMessage() == ClientException::DOCUMENT_CONFLICT ) {
					$doc = self::getById( $this->getId() );
					$this->_rev = $doc->_rev;
				}
				else {
					throw $e;
				}
			}
		}

		throw new \Exception( 'Couldn\'t overwrite "' . $this->get . '" after 100 attempts.' );
	}

}