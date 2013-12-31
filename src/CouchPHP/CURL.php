<?php 

namespace CouchPHP;

class CURL {
	
	static private function exec( $ch ) {
		$output = curl_exec( $ch );
		if( $output === false ) {
			throw new \Exception( curl_error( $ch ) );
		}
		
		curl_close( $ch );
		return $output;

	}

	static public function get( $url ) {
		$ch = curl_init( $url );
	
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );

		return self::exec( $ch );
	}

	static public function post( $url, $data ) {
		$ch = curl_init( $url );
	
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data  );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

		return self::exec( $ch );
	}

	static public function put( $url, $data ) {
		$ch = curl_init( $url );
	
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

		return self::exec( $ch );
	}
		
}