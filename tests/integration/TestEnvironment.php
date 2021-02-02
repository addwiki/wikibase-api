<?php

namespace Wikibase\Api\Test;

use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\WikibaseFactory;

/**
 * @author Addshore
 */
class TestEnvironment {

	public static function newDefault() {
		return new self();
	}

	private $factory;

	public function __construct() {
		$apiUrl = getenv( 'ADDWIKI_MW_API' );

		if ( !$apiUrl ) {
			$apiUrl = "http://localhost:8877/api.php";
		}

		if ( substr( $apiUrl, -7 ) !== 'api.php' ) {
			$msg = "URL incorrect: $apiUrl"
				. " (Set the ADDWIKI_MW_API environment variable correctly)";
			throw new Exception( $msg );
		}

		$this->factory = new WikibaseFactory(
			new MediawikiApi( $apiUrl ),
			$this->newDataValueDeserializer(),
			new DataValueSerializer()
		);
	}

	public function getFactory() {
		return $this->factory;
	}

	private function newDataValueDeserializer() {
		return new DataValueDeserializer(
			[
				'boolean' => 'DataValues\BooleanValue',
				'number' => 'DataValues\NumberValue',
				'string' => 'DataValues\StringValue',
				'unknown' => 'DataValues\UnknownValue',
				'globecoordinate' => 'DataValues\Geo\Values\GlobeCoordinateValue',
				'monolingualtext' => 'DataValues\MonolingualTextValue',
				'multilingualtext' => 'DataValues\MultilingualTextValue',
				'quantity' => 'DataValues\QuantityValue',
				'time' => 'DataValues\TimeValue',
			]
		);
	}

}
