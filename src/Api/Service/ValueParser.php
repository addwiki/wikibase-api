<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use Deserializers\Deserializer;
use GuzzleHttp\Promise\Promise;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use RuntimeException;

/**
 * @access private
 *
 * @author Addshore
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
	 * @param string|string[] $inputValues one or more
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @returns DataValue|DataValue[] if array parsed object has same array key as value
	 */
	public function parse( $inputValues, $parser ) {
		return $this->parseAsync( $inputValues, $parser )->wait();
	}

	/**
	 * @since 0.7
	 *
	 * @param string|string[] $inputValues one or more
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @returns Promise of a DataValue object or array of DataValue objects with same keys as values
	 */
	public function parseAsync( $inputValues, $parser ) {
		$promise = $this->api->getRequestAsync(
			new SimpleRequest(
				'wbparsevalue',
				array(
					'parser' => $parser,
					'values' => implode( '|', $inputValues ),
				)
			)
		);

		return $promise->then(
			function ( $result ) use ( $inputValues ) {
				if ( is_array( $inputValues ) ) {
					$indexedResults = array();
					foreach ( $result['results'] as $resultElement ) {
						if ( in_array( $resultElement['raw'], $inputValues ) ) {
							$indexedResults[array_search( $resultElement['raw'], $inputValues )] =
								$this->dataValueDeserializer->deserialize( $resultElement );
						} else {
							throw new RuntimeException( "Failed to match parse results with input data" );
						}
					}

					return $indexedResults;
				} else {
					return $this->dataValueDeserializer->deserialize( $result['results'][0] );
				}
			}
		);
	}

}
