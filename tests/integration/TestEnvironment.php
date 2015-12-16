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
		$this->factory = new WikibaseFactory(
			new MediawikiApi( 'http://localhost/w/api.php' ),
			$this->newDataValueDeserializer(),
			new DataValueSerializer()
		);
	}

	public function getFactory() {
		return $this->factory;
	}

	private function newDataValueDeserializer() {
		return new DataValueDeserializer(
			array(
				'boolean' => 'DataValues\BooleanValue',
				'number' => 'DataValues\NumberValue',
				'string' => 'DataValues\StringValue',
				'unknown' => 'DataValues\UnknownValue',
				'globecoordinate' => 'DataValues\GlobeCoordinateValue',
				'monolingualtext' => 'DataValues\MonolingualTextValue',
				'multilingualtext' => 'DataValues\MultilingualTextValue',
				'quantity' => 'DataValues\QuantityValue',
				'time' => 'DataValues\TimeValue',
			)
		);
	}

}
