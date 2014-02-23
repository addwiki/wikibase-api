<?php

namespace Wikibase\Api;

use DataValues\Serializers\DataValueSerializer;
use InvalidArgumentException;
use Mediawiki\Api\DataModel\NewRevision;
use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\SerializerFactory;

/**
 * @author Adam Shorland
 */
class RevisionSaver {

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
	 * @param NewRevision $revision
	 *
	 * @throws InvalidArgumentException
	 *
	 * @returns bool
	 */
	public function save( NewRevision $revision ) {
		$serializer = $this->serializerFactory->newEntitySerializer();
		$entity = $revision->getContent();

		if( !$entity instanceof Entity ) {
			throw new InvalidArgumentException( 'Revision is not of an entity' );
		}

		$serialized = $serializer->serialize( $entity );

		$params = array(
			'data' => json_encode( $serialized ),
			'token' => $this->api->getToken()
		);

		$revId = $revision->getId();
		if( !is_null( $revId ) ) {
			$params['baserevid'] = $revId;
		}

		$entityId = $entity->getId();
		if( !is_null( $entityId ) ) {
			$params['id'] = $entityId->getPrefixedId();
		} else {
			$params['new'] = $entity->getType();
		}

		$editInfo = $revision->getEditInfo();
		if( $editInfo->getBot() ) {
			$params['bot'] = true;
		}
		if( $editInfo->getMinor() ) {
			$params['minor'] = true;
		}
		$summary = $editInfo->getSummary();
		if( !empty( $summary ) ) {
			$params['summary'] = $summary;
		}

		$this->api->postAction( 'wbeditentity', $params );
		return true;
	}

} 