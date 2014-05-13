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
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param $value
	 * @param $parser
	 *
	 * @internal param string $value
	 *
	 * @returns DataValue
	 */
	public function parse( $value, $parser ) {
		$result = $this->api->getAction( 'wbparsevalue', array( 'parser' => $parser, 'values' => $value ) );
		$deserializer = new DataValueDeserializer( array(
			'number' => 'DataValues\NumberValue',
			'string' => 'DataValues\StringValue',
			'globecoordinate' => 'DataValues\GlobeCoordinateValue',
			'monolingualtext' => 'DataValues\MonolingualTextValue',
			'multilingualtext' => 'DataValues\MultilingualTextValue',
			'quantity' => 'DataValues\QuantityValue',
			'time' => 'DataValues\TimeValue',
			'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue', ) );
		$resultobject = $deserializer->deserialize( $result["results"][0] );
		return $resultobject;
	}

} 