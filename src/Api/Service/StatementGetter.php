<?php

namespace Wikibase\Api\Service;

use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
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
		$params = array(
			'claim' => $guid,
		);

		$result = $this->api->getRequest( new SimpleRequest( 'wbgetclaims', $params ) );

		$statementSerialization = array_shift( array_shift( $result['claims'] ) );

		return $this->statementDeserializer->deserialize( $statementSerialization );
	}

} 