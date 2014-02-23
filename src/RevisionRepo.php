<?php

namespace Wikibase\Api;

use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\DataModel\Revision;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\SiteLink;

/**
 * @author Adam Shorland
 */
class RevisionRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var Deserializer
	 */
	protected $entityDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $entityDeserializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $entityDeserializer ) {
		$this->api = $api;
		$this->entityDeserializer = $entityDeserializer;
	}

	/**
	 * @param string|EntityId $id
	 * @returns Revision
	 */
	public function getFromId( $id ) {
		if( $id instanceof EntityId ) {
			$id = $id->getPrefixedId();
		}

		return $this->newRevisionFromResult( $this->getEntityResultById( $id ) );
	}
	
	/**
	 * @param string $id
	 * @return array
	 */
	private function getEntityResultById( $id ) {
		$result = $this->api->getAction( 'wbgetentities', array( 'ids' => $id ) );
		return array_shift( $result['entities'] );
	}

	/**
	 * @param string|SiteLink $siteLink
	 * @returns Revision
	 */
	public function getFromSiteLink( $siteLink ) {
		$result = $this->api->getAction( 'wbgetentities', array( 'sites' => $siteLink->getSiteId(), 'titles' => $siteLink->getPageName() ) );
		return array_shift( $result['entities'] );
	}

	/**
	 * @param string $siteId
	 * @param string $title
	 * @returns Revision
	 */
	public function getFromSiteAndTitle( $siteId, $title ) {
		$result = $this->api->getAction( 'wbgetentities', array( 'sites' => $siteId, 'titles' => $title ) );
		return array_shift( $result['entities'] );
	}
	
	/**
	 * @param array $entityResult
	 * @returns Revision
	 */
	private function newRevisionFromResult( array $entityResult ) {
		return new Revision(
			$this->entityDeserializer->deserialize( $entityResult ),
			$entityResult['pageid'],
			$entityResult['lastrevid'],
			null,
			null,
			$entityResult['modified']
		);
	}

}
