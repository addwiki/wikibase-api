<?php

namespace Wikibase\Api\Service;

use Deserializers\Deserializer;
use InvalidArgumentException;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;
use RuntimeException;
use Serializers\Serializer;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

/**
 * @access private
 *
 * @author Addshore
 */
class RevisionSaver {

	/**
	 * @var WikibaseApi
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
	 * @param WikibaseApi $api
	 * @param Deserializer $entityDeserializer
	 */
	public function __construct( WikibaseApi $api, Deserializer $entityDeserializer, Serializer $entitySerializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
		$this->entitySerializer = $entitySerializer;
	}

	/**
	 * @since 0.1
	 * @param Revision $revision
	 * @param EditInfo|null $editInfo
	 *
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 * @returns Item|Property new version of the entity
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ) {
		if( !$revision->getContent()->getData() instanceof EntityDocument ) {
			throw new RuntimeException( 'Can only save Content of EntityDocuments' );
		}

		/** @var Item|Property $entity */
		$entity = $revision->getContent()->getData();
		$serialized = $this->entitySerializer->serialize( $entity );

		$params = array(
			'data' => json_encode( $serialized )
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
			$params['summary'] = 'Edited a ' . $entity->getType();

		} else {
			$params['new'] = $entity->getType();
		}

		// If no editInfo is explicitly passed call back to the one in the revision?
		if( $editInfo === null ) {
			$editInfo = $revision->getEditInfo();
		}

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

		$result = $this->api->postRequest( 'wbeditentity', $params, $editInfo );
		return $this->entityDeserializer->deserialize( $result['entity'] );
	}

} 