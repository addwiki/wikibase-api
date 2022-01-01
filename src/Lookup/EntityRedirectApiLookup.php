<?php

namespace Addwiki\Wikibase\Api\Lookup;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use BadMethodCallException;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityRedirectLookup;
use Wikibase\DataModel\Services\Lookup\EntityRedirectLookupException;

/**
 * @access private
 */
class EntityRedirectApiLookup implements EntityRedirectLookup {

	private ActionApi $api;

	public function __construct( ActionApi $api ) {
		$this->api = $api;
	}

	/**
	 * @see EntityRedirectLookup::getRedirectIds
	 */
	public function getRedirectIds( EntityId $targetId ) {
		// TODO: Implement getRedirectIds() method.
		// Note: this is hard currently as we have to discover the namespace of the entity type?
		throw new BadMethodCallException( 'Not implemented yet' );
	}

	/**
	 * @see EntityRedirectLookup::getRedirectForEntityId
	 * @return ItemId|PropertyId
	 */
	public function getRedirectForEntityId( EntityId $entityId, $forUpdate = '' ) {
		$entityIdSerialization = $entityId->getSerialization();

		$params = [ 'ids' => $entityIdSerialization ];
		$result = $this->api->request( ActionRequest::simpleGet( 'wbgetentities', $params ) );

		$entitiesData = $result['entities'];
		if ( !array_key_exists( $entityIdSerialization, $entitiesData ) ) {
			throw new EntityRedirectLookupException( $entityId, sprintf( 'Failed to get %s', $entityIdSerialization ) );
		}

		$entityData = $entitiesData[$entityIdSerialization];
		if ( !array_key_exists( 'redirects', $entityData ) ) {
			throw new EntityRedirectLookupException( $entityId, sprintf( '%s is not a redirect', $entityIdSerialization ) );
		}

		$entityIdParser = new BasicEntityIdParser();
		return $entityIdParser->parse( $entityData['redirects']['to'] );
	}

}
