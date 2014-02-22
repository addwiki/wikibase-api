<?php

namespace Wikibase\Api\Repos;

use DataValues\Deserializers\DataValueDeserializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\DataModel\EntityRevision;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\SiteLink;

class EntityRevisionRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var DeserializerFactory
	 */
	protected $deserializerFactory;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
		$this->deserializerFactory =  new DeserializerFactory(
			new DataValueDeserializer( array(
				'number' => 'DataValues\NumberValue',
				'string' => 'DataValues\StringValue',
				'globecoordinate' => 'DataValues\GlobeCoordinateValue',
				'monolingualtext' => 'DataValues\MonolingualTextValue',
				'multilingualtext' => 'DataValues\MultilingualTextValue',
				'quantity' => 'DataValues\QuantityValue',
				'time' => 'DataValues\TimeValue',
				'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue', )
			),
			new BasicEntityIdParser()
		);
	}

	/**
	 * @param string|EntityId $id
	 * @returns Entity
	 */
	public function getFromId( $id ) {
		if( $id instanceof EntityId ) {
			$id = $id->getPrefixedId();
		}

		$result = $this->api->getAction( 'wbgetentities', array( 'ids' => $id ) );

		$deserializer = $this->deserializerFactory->newEntityDeserializer();
		$entityResult = array_shift( $result['entities'] );
		$entity = $deserializer->deserialize( $entityResult );

		return new EntityRevision( $entity, $entityResult['lastrevid'] );
	}

	/**
	 * @param string|SiteLink $siteLink
	 * @returns Entity
	 */
	public function getFromSiteLink( $siteLink ) {
		//TODO
	}

}