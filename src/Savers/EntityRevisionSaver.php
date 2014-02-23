<?php

namespace Wikibase\Api\Savers;

use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\DataModel\EntityRevision;
use Wikibase\DataModel\SerializerFactory;

class EntityRevisionSaver {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var SerializerFactory
	 */
	protected $serializerFactory;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
		$this->serializerFactory =  new SerializerFactory(
			new DataValueSerializer()
		);
	}

	/**
	 * @param EntityRevision $entityRevision
	 * @returns bool
	 */
	public function save( EntityRevision $entityRevision ) {
		$serializer = $this->serializerFactory->newEntitySerializer();
		$entity = $entityRevision->getData();
		$serialized = $serializer->serialize( $entity );

		$params = array(
			'data' => json_encode( $serialized ),
			'token' => $this->api->getToken()
		);

		$baseRevId = $entityRevision->getLastRevId();
		if( !is_null( $baseRevId ) ) {
			$params['baserevid'] = $baseRevId;
		}

		$entityId = $entity->getId();
		if( !is_null( $entityId ) ) {
			$params['id'] = $entityId->getPrefixedId();
		} else {
			$params['new'] = $entity->getType();
		}

		$this->api->postAction( 'wbeditentity', $params );
		return true;
	}

} 