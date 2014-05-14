<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;
use Wikibase\DataModel\Reference;

/**
 * @author Adam Shorland
 */
class ReferenceRemover {

	/**
	 * @param Reference|string $reference Reference object or hash
	 * @param Claim|ClaimGuid|string $target Claim object or GUID
	 */
	public function setReference( $reference, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 