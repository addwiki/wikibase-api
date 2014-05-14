<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;
use Wikibase\DataModel\Reference;

/**
 * @author Adam Shorland
 */
class ReferenceSetter {

	/**
	 * @param Reference $reference
	 * @param Claim|ClaimGuid|string $target Claim object or GUID
	 */
	public function setReference( Reference $reference, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 