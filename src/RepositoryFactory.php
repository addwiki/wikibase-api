<?php

namespace Wikibase\Api;

use DataValues\Deserializers\DataValueDeserializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\Repos\EntityRevisionRepo;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;

class RepositoryFactory {

	private $api;

	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	public function newEntityRevisionRepo() {
		return new EntityRevisionRepo(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	private function newDataModelDeserializerFactory() {
		return new DeserializerFactory(
			$this->newDataValueDeserializer(),
			new BasicEntityIdParser()
		);
	}

	private function newDataValueDeserializer() {
		return new DataValueDeserializer( array(
				'number' => 'DataValues\NumberValue',
				'string' => 'DataValues\StringValue',
				'globecoordinate' => 'DataValues\GlobeCoordinateValue',
				'monolingualtext' => 'DataValues\MonolingualTextValue',
				'multilingualtext' => 'DataValues\MultilingualTextValue',
				'quantity' => 'DataValues\QuantityValue',
				'time' => 'DataValues\TimeValue',
				'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue', )
		);
	}

}
