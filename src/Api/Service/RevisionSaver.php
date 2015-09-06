<?php

namespace Wikibase\Api\Service;

use Deserializers\Deserializer;
use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Revision;
use RuntimeException;
use Serializers\Serializer;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\PropertyContent;

/**
 * @author Adam Shorland
 */
class RevisionSaver {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var Deserializer
	 */
	private $entityDeserializer;

	/**
	 * @var Serializer
	 */
	private $entitySerializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $entityDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $entityDeserializer, Serializer $entitySerializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
		$this->entitySerializer = $entitySerializer;
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
		if( !in_array( $revision->getContent()->getModel(), array( PropertyContent::MODEL, ItemContent::MODEL ) ) ) {
			throw new RuntimeException( 'Can not save revisions with the given content model' );
		}

		/** @var Item|Property $entity */
		$entity = $revision->getContent()->getData();
		$serialized = $this->entitySerializer->serialize( $entity );

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

			// Always clear so that removing elements is possible
			$params['clear'] = 'true';
			// Add more detail to the default "Cleared an entity" summary
			// Note: this is later overridden if a summary is provided in the EditInfo
			$params['summary'] = 'Edited an ' . $entity->getType();

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

		$result = $this->api->postRequest( new SimpleRequest( 'wbeditentity', $params ) );
		return $this->entityDeserializer->deserialize( $result['entity'] );
	}

} 