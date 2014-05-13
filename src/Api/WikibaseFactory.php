<?php

namespace Wikibase\Api;

use DataValues\Deserializers\DataValueDeserializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\Service\RevisionRepo;
use Wikibase\Api\Service\RevisionSaver;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;

/**
 * @author Jeroen De Dauw
 */
class WikibaseFactory {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @return RevisionSaver
	 */
	public function newRevisionSaver() {
		return new RevisionSaver( $this->api );
	}

	/**
	 * @return RevisionRepo
	 */
	public function newRevisionRepo() {
		return new RevisionRepo(
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
