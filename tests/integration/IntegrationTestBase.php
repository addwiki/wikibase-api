<?php

namespace Wikibase\Api\Test;

use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\WikibaseFactory;

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var WikibaseFactory
	 */
	protected $factory;

	protected function setUp() {
		parent::setUp();
		$this->factory = new WikibaseFactory(
			new MediawikiApi( 'http://localhost/w/api.php' ),
			$this->newDataValueDeserializer(),
			new DataValueSerializer()
		);
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

	// Needed to stop phpunit complaining
	public function testFactory() {
		$this->assertInstanceOf( 'Wikibase\Api\WikibaseFactory', $this->factory );
	}

	protected function tearDown() {
		parent::tearDown();
		unset( $this->factory );
	}

} 