<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;

/**
 * @author Adam Shorland
 */
class ClaimRemover {

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
	 * @param Claim|ClaimGuid|string $claim Claim object or GUID
	 */
	public function remove( $claim ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 