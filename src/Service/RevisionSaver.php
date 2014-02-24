<?php

namespace Wikibase\Api\Service;

use DataValues\Serializers\DataValueSerializer;
use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Revision;
use RuntimeException;
use Wikibase\Api\DataModel\ItemContent;
use Wikibase\Api\DataModel\PropertyContent;
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
	 * @param Revision $revision
	 *
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 * @returns bool
	 */
	public function save( Revision $revision ) {
		$serializer = $this->serializerFactory->newEntitySerializer();

		if( !in_array( $revision->getContent()->getModel(), array( PropertyContent::contentModel, ItemContent::contentModel ) ) ) {
			throw new RuntimeException( 'Can not save revisions with the given content model' );
		}

		/** @var Entity $entity */
		$entity = $revision->getContent()->getNativeData();
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