<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;

/**
 * @access private
 *
 * @author Adam Shorland
 * @author Thomas Arrow
 */
class ValueParser {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var Deserializer
	 */
	private $dataValueDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $dataValueDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $dataValueDeserializer ) {
		$this->api = $api;
		$this->dataValueDeserializer = $dataValueDeserializer;
	}

	/**
	 * @since 0.2
	 *
	 * @param string $value
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @internal param string $value
	 *
	 * @returns DataValue
	 */
	public function parse( $value, $parser ) {
		$result = $this->api->getRequest( new SimpleRequest(
			'wbparsevalue',
			array( 'parser' => $parser, 'values' => $value )
		) );
		return $this->dataValueDeserializer->deserialize( $result['results'][0] );
	}

} 