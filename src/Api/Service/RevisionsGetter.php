<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Mediawiki\DataModel\Revisions;
use Addwiki\Wikibase\DataModel\ItemContent;
use Addwiki\Wikibase\DataModel\PropertyContent;
use Deserializers\Deserializer;
use InvalidArgumentException;
use RuntimeException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\SiteLink;

/**
 * @access private
 */
class RevisionsGetter {

	protected ActionApi $api;

	private Deserializer $entityDeserializer;

	public function __construct( ActionApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
	}

	/**
	 * Get revisions for the entities identified using as few requests as possible.
	 *
	 * @param array $identifyingInfoArray Can include the following:
	 *     EntityId EntityId objects
	 *     SiteLink SiteLink objects
	 *     string Serialized entity ids (these are not validated before passing to the api)
	 */
	public function getRevisions( array $identifyingInfoArray ): Revisions {
		$entityIdStrings = [];
		$siteLinksStringMapping = [];

		foreach ( $identifyingInfoArray as $someInfo ) {
			if ( $someInfo instanceof EntityId ) {
				$entityIdStrings[] = $someInfo->getSerialization();
			} elseif ( $someInfo instanceof SiteLink ) {
				$siteLinksStringMapping[ $someInfo->getSiteId() ][] = $someInfo->getPageName();
			} elseif ( is_string( $someInfo ) ) {
				$entityIdStrings[] = $someInfo;
			} else {
				throw new InvalidArgumentException( 'Unexpected $identifyingInfoArray in RevisionsGetter::getRevisions' );
			}
		}

		// The below makes as few requests as possible to get the Revisions required!
		$gotRevisionsFromIds = false;
		$revisions = new Revisions();
		if ( !empty( $siteLinksStringMapping ) ) {
			foreach ( $siteLinksStringMapping as $site => $siteLinkStrings ) {
				$params = [ 'sites' => $site ];
				if ( !$gotRevisionsFromIds && !empty( $entityIdStrings ) ) {
					$params['ids'] = implode( '|', $entityIdStrings );
					$gotRevisionsFromIds = true;
				}

				$params['titles'] = implode( '|', $siteLinkStrings );
				$result = $this->api->request( ActionRequest::simpleGet( 'wbgetentities', $params ) );
				$resultRevisions = $this->newRevisionsFromResult( $result['entities'] );
				$revisions->addRevisions( $resultRevisions );

			}
		} else {
			$params = [ 'ids' => implode( '|', $entityIdStrings ) ];
			$result = $this->api->request( ActionRequest::simpleGet( 'wbgetentities', $params ) );
			$resultRevisions = $this->newRevisionsFromResult( $result['entities'] );
			$revisions->addRevisions( $resultRevisions );
		}

		return $revisions;
	}

	/**
	 * @todo this could be factored into a different class?
	 */
	private function newRevisionsFromResult( array $entitiesResult ): Revisions {
		$revisions = new Revisions();
		foreach ( $entitiesResult as $entityResult ) {
			if ( array_key_exists( 'missing', $entityResult ) ) {
				continue;
			}

			$revisions->addRevision( new Revision(
				$this->getContentFromEntity( $this->entityDeserializer->deserialize( $entityResult ) ),
				new PageIdentifier( null, $entityResult['pageid'] ),
				$entityResult['lastrevid'],
				null,
				null,
				$entityResult['modified']
			) );
		}

		return $revisions;
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
				throw new RuntimeException( 'I cant get a content for this type of entity' );
		}
	}

}
