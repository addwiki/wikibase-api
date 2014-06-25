<?php


namespace Wikibase\Api\Service;


use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @author Adam Shorland
 */
class ClaimCreator {

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
	 * @since 0.2
	 * @param Claim $label
	 * @param EntityId|Entity $target
	 */
	public function createClaim( Claim $claim, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 