<?php

namespace Wikibase\Api\Service;

use DataValues\Serializers\DataValueSerializer;
use Deserializers\Deserializer;
use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Revision;
use RuntimeException;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\PropertyContent;
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
	 * @var Deserializer
	 */
	private $entityDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $entityDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
		$this->serializerFactory =  new SerializerFactory(
			new DataValueSerializer()
		);
	}

	/**
	 * @since 0.1
	 * @param Revision $revision
	 *
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 * @returns Item|Property new version of the entity
	 */
	public function save( Revision $revision ) {
		$serializer = $this->serializerFactory->newEntitySerializer();

		if( !in_array( $revision->getContent()->getModel(), array( PropertyContent::MODEL, ItemContent::MODEL ) ) ) {
			throw new RuntimeException( 'Can not save revisions with the given content model' );
		}

		/** @var Item|Property $entity */
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
			$params['id'] = $entityId->getSerialization();
			if( $entity->isEmpty() ) {
				$params['clear'] = 'true';
			}
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

		$result = $this->api->postAction( 'wbeditentity', $params );
		return $this->entityDeserializer->deserialize( $result['entity'] );
	}

} 