<?php

namespace Wikibase\Api;

use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\EditFlags;
use Wikibase\DataModel\SerializerFactory;

/**
 * @author Adam Shorland
 */
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
	 * @param EditFlags|null $editFlags
	 *
	 * @returns bool
	 */
	public function save( EntityRevision $entityRevision, EditFlags $editFlags = null ) {
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

		if( !is_null( $editFlags ) ) {
			if( $editFlags->getBot() ) {
				$params['bot'] = true;
			}
			if( $editFlags->getMinor() ) {
				$params['minor'] = true;
			}
			$summary = $editFlags->getSummary();
			if( !empty( $summary ) ) {
				$params['summary'] = $summary;
			}
		}

		$this->api->postAction( 'wbeditentity', $params );
		return true;
	}

} 