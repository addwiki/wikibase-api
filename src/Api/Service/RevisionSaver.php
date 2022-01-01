<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Wikibase\Api\WikibaseApi;
use Deserializers\Deserializer;
use InvalidArgumentException;
use RuntimeException;
use Serializers\Serializer;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

/**
 * @access private
 */
class RevisionSaver {

	protected WikibaseApi $api;

	private Deserializer $entityDeserializer;

	private Serializer $entitySerializer;

	public function __construct( WikibaseApi $api, Deserializer $entityDeserializer, Serializer $entitySerializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
		$this->entitySerializer = $entitySerializer;
	}

	/**
	 * @param EditInfo|null $editInfo
	 *
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 * @return Item|Property new version of the entity
	 */
	public function save( Revision $revision, EditInfo $editInfo = null ): object {
		if ( !$revision->getContent()->getData() instanceof EntityDocument ) {
			throw new RuntimeException( 'Can only save Content of EntityDocuments' );
		}

		/** @var Item|Property $entity */
		$entity = $revision->getContent()->getData();
		$serialized = $this->entitySerializer->serialize( $entity );

		$params = [
			'data' => json_encode( $serialized )
		];

		$revId = $revision->getId();
		if ( $revId !== null ) {
			$params['baserevid'] = $revId;
		}

		$entityId = $entity->getId();
		if ( $entityId !== null ) {
			$params['id'] = $entityId->getSerialization();

			// If we are provided an empty entity, then set the clear flag
			if ( $entity->isEmpty() ) {
				$params['clear'] = true;
			}

			// Add more detail to the default "Cleared an entity" summary
			// Note: this is later overridden if a summary is provided in the EditInfo
			$params['summary'] = 'Edited a ' . $entity->getType();

		} else {
			$params['new'] = $entity->getType();
		}

		// If no editInfo is explicitly passed call back to the one in the revision?
		if ( $editInfo === null ) {
			$editInfo = $revision->getEditInfo();
		}

		$result = $this->api->postRequest( 'wbeditentity', $params, $editInfo );
		return $this->entityDeserializer->deserialize( $result['entity'] );
	}

}
