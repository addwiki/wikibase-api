<?php

namespace Wikibase\Api\Lookup;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Services\Lookup\EntityRedirectLookup;
use Wikibase\DataModel\Services\Lookup\EntityRedirectLookupException;

/**
 * @author Addshore
 *
 * @access private
 */
class EntityRedirectApiLookup implements EntityRedirectLookup {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @see EntityRedirectLookup::getRedirectIds
	 */
	public function getRedirectIds( EntityId $targetId ) {
		// TODO: Implement getRedirectIds() method.
		// Note: this is hard currently as we have to discover the namespace of the entity type?
		throw new \BadMethodCallException('Not implemented yet');
	}

	/**
	 * @see EntityRedirectLookup::getRedirectForEntityId
	 */
	public function getRedirectForEntityId( EntityId $entityId, $forUpdate = '' ) {
		$entityIdSerialization = $entityId->getSerialization();

		$params = array( 'ids' => $entityIdSerialization );
		$result = $this->api->getRequest( new SimpleRequest( 'wbgetentities', $params ) );

		$entitiesData = $result['entities'];
		if( !array_key_exists( $entityIdSerialization, $entitiesData ) ) {
			throw new EntityRedirectLookupException( $entityId, "Failed to get $entityIdSerialization" );
		}

		$entityData = $entitiesData[$entityIdSerialization];
		if( !array_key_exists( 'redirects', $entityData ) ) {
			throw new EntityRedirectLookupException( $entityId, "$entityIdSerialization is not a redirect" );
		}

		$entityIdParser = new BasicEntityIdParser();
		return $entityIdParser->parse( $entityData['redirects']['to'] );
	}

}
