<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;

/**
 * @author Adam Shorland
 */
class ClaimRemover {

	/**
	 * @since 0.2
	 * @param Claim|ClaimGuid|string $claim Claim object or GUID
	 */
	public function removeClaim( $claim ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 