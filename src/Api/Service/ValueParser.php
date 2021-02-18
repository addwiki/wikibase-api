<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use DataValues\DataValue;
use Deserializers\Deserializer;
use GuzzleHttp\Promise\PromiseInterface;
use RuntimeException;

/**
 * @access private
 */
class ValueParser {

	private MediawikiApi $api;

	private Deserializer $dataValueDeserializer;

	public function __construct( MediawikiApi $api, Deserializer $dataValueDeserializer ) {
		$this->api = $api;
		$this->dataValueDeserializer = $dataValueDeserializer;
	}

	/**
	 * @param string|string[] $inputValues one or more
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @return DataValue|DataValue[] if array parsed object has same array key as value
	 */
	public function parse( $inputValues, string $parser ) {
		return $this->parseAsync( $inputValues, $parser )->wait();
	}

	/**
	 * @param string|string[] $inputValues one or more
	 * @param string $parser Id of the ValueParser to use
	 *
	 * @return PromiseInterface of a DataValue object or array of DataValue objects with same keys as values
	 */
	public function parseAsync( $inputValues, string $parser ): PromiseInterface {
		$promise = $this->api->getRequestAsync(
			new SimpleRequest(
				'wbparsevalue',
				[
					'parser' => $parser,
					'values' => implode( '|', $inputValues ),
				]
			)
		);

		return $promise->then(
			function ( $result ) use ( $inputValues ) {
				if ( is_array( $inputValues ) ) {
					$indexedResults = [];
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
