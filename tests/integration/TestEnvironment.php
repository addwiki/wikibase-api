<?php

namespace Addwiki\Wikibase\Api\Tests\Integration;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Wikibase\Api\WikibaseFactory;
use Addwiki\Wikibase\DataModel\DataModelFactory;
use DataValues\BooleanValue;
use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Geo\Values\GlobeCoordinateValue;
use DataValues\MonolingualTextValue;
use DataValues\MultilingualTextValue;
use DataValues\NumberValue;
use DataValues\QuantityValue;
use DataValues\Serializers\DataValueSerializer;
use DataValues\StringValue;
use DataValues\TimeValue;
use DataValues\UnknownValue;
use Exception;

class TestEnvironment {

	public static function newDefault(): TestEnvironment {
		return new self();
	}

	private WikibaseFactory $factory;

	public function __construct() {
		$apiUrl = getenv( 'ADDWIKI_MW_API' );

		if ( !$apiUrl ) {
			$apiUrl = "http://localhost:8877/api.php";
		}

		if ( substr( $apiUrl, -7 ) !== 'api.php' ) {
			$msg = sprintf( 'URL incorrect: %s', $apiUrl )
				. " (Set the ADDWIKI_MW_API environment variable correctly)";
			throw new Exception( $msg );
		}

		$this->factory = new WikibaseFactory(
			new ActionApi( $apiUrl ),
			new DataModelFactory(
				$this->newDataValueDeserializer(),
				new DataValueSerializer()
			)
		);
	}

	public function getFactory(): WikibaseFactory {
		return $this->factory;
	}

	private function newDataValueDeserializer(): DataValueDeserializer {
		return new DataValueDeserializer(
			[
				'boolean' => BooleanValue::class,
				'number' => NumberValue::class,
				'string' => StringValue::class,
				'unknown' => UnknownValue::class,
				'globecoordinate' => GlobeCoordinateValue::class,
				'monolingualtext' => MonolingualTextValue::class,
				'multilingualtext' => MultilingualTextValue::class,
				'quantity' => QuantityValue::class,
				'time' => TimeValue::class,
			]
		);
	}

}
