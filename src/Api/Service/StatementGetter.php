<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Wikibase\DataModel\Deserializers\StatementDeserializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @since 0.5
 *
 * @author Adam Shorland
 */
class StatementGetter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var StatementDeserializer
	 */
	private $statementDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param StatementDeserializer $statementDeserializer
	 */
	public function __construct( MediawikiApi $api, StatementDeserializer $statementDeserializer ) {
		$this->api = $api;
		$this->statementDeserializer = $statementDeserializer;
	}

	/**
	 * @param string $guid
	 *
	 * @return Statement
	 */
	public function getFromGuid( $guid ) {
		$params = array(
			'claim' => $guid,
		);

		$result = $this->api->getRequest( new SimpleRequest( 'wbgetclaims', $params ) );

		$statementSerialization = array_shift( array_shift( $result['claims'] ) );

		return $this->statementDeserializer->deserialize( $statementSerialization );
	}

} 