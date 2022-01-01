<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Content;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Wikibase\DataModel\ItemContent;
use Addwiki\Wikibase\DataModel\PropertyContent;
use Deserializers\Deserializer;
use RuntimeException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\SiteLink;

/**
 * @access private
 */
class RevisionGetter {

	protected ActionApi $api;

	protected Deserializer $entityDeserializer;

	public function __construct( ActionApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
	}

	/**
	 * @param string|EntityId $id
	 */
	public function getFromId( $id ): ?Revision {
		if ( $id instanceof EntityId ) {
			$id = $id->getSerialization();
		}

		$result = $this->api->request( ActionRequest::simpleGet( 'wbgetentities', [ 'ids' => $id ] ) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	public function getFromSiteLink( SiteLink $siteLink ): ?Revision {
		$result = $this->api->request( ActionRequest::simpleGet(
			'wbgetentities',
			[ 'sites' => $siteLink->getSiteId(), 'titles' => $siteLink->getPageName() ]
		) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	public function getFromSiteAndTitle( string $siteId, string $title ): ?Revision {
		$result = $this->api->request( ActionRequest::simpleGet(
			'wbgetentities',
			[ 'sites' => $siteId, 'titles' => $title ]
		) );
		return $this->newRevisionFromResult( array_shift( $result['entities'] ) );
	}

	private function newRevisionFromResult( array $entityResult ): ?Revision {
		if ( array_key_exists( 'missing', $entityResult ) ) {
			return null; // Throw an exception?
		}

		return new Revision(
			$this->getContentFromEntity( $this->entityDeserializer->deserialize( $entityResult ) ),
			new PageIdentifier( null, (int)$entityResult['pageid'] ),
			$entityResult['lastrevid'],
			null,
			null,
			$entityResult['modified']
		);
	}

	/**
	 * @param Item|Property $entity
	 *
	 * @throws RuntimeException
	 * @return ItemContent|PropertyContent|void
	 * @todo this could be factored into a different class?
	 */
	private function getContentFromEntity( $entity ) {
		switch ( $entity->getType() ) {
			case Item::ENTITY_TYPE:
				return new ItemContent( $entity );
			case Property::ENTITY_TYPE:
				return new PropertyContent( $entity );
			default:
				// We can always create a default content object, we just dont know the model
				return new Content( $entity, 'addwiki-unknown-wikibase-entity-content-' . $entity->getType() );
		}
	}

}
