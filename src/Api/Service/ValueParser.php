<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use Deserializers\Deserializer;
use GuzzleHttp\Promise\Promise;
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
	 * @returns DataValue
	 */
	public function parse( $value, $parser ) {
		return $this->parseAsync( $value, $parser )->wait();
	}

	/**
	 * @since 0.7
	 *
	 * @param string $value
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @returns Promise of a DataValue object
	 */
	public function parseAsync( $value, $parser ) {
		$promise = $this->api->getRequestAsync(
			new SimpleRequest(
				'wbparsevalue',
				array( 'parser' => $parser, 'values' => $value )
			)
		);

		return $promise->then(
			function ( $result ) {
				return $this->dataValueDeserializer->deserialize( $result['results'][0] );
			}
		);
	}

}
