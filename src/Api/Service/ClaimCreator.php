<?php


namespace Wikibase\Api\Service;


use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @author Adam Shorland
 */
class ClaimCreator {

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