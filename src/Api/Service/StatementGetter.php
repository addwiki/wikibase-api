<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Deserializers\Deserializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @access private
 *
 * @author Addshore
 */
class StatementGetter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var Deserializer
	 */
	private $statementDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $statementDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $statementDeserializer ) {
		$this->api = $api;
		$this->statementDeserializer = $statementDeserializer;
	}

	/**
	 * @param string $guid
	 *
	 * @return Statement
	 */
	public function getFromGuid( $guid ) {
		$params = [
			'claim' => $guid,
		];

		$result = $this->api->getRequest( new SimpleRequest( 'wbgetclaims', $params ) );
		$arrayShift = array_shift( $result['claims'] );

		$statementSerialization = array_shift( $arrayShift );

		return $this->statementDeserializer->deserialize( $statementSerialization );
	}

}
