<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use DataValues\Deserializers\DataValueDeserializer;
use Mediawiki\Api\MediawikiApi;

/**
 * @author Adam Shorland
 * @author Thomas Arrow
 */
class ValueParser {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var DataValueDeserializer
	 */
	private $dataValueDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param DataValueDeserializer $dataValueDeserializer
	 */
	public function __construct( MediawikiApi $api, DataValueDeserializer $dataValueDeserializer ) {
		$this->api = $api;
		$this->dataValueDeserializer = $dataValueDeserializer;
	}

	/**
	 * @param string $value
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @internal param string $value
	 *
	 * @returns DataValue
	 */
	public function parse( $value, $parser ) {
		$result = $this->api->getAction( 'wbparsevalue', array( 'parser' => $parser, 'values' => $value ) );
		return $this->dataValueDeserializer->deserialize( $result['results'][0] );
	}

} 