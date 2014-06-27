<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;
use Wikibase\DataModel\Reference;

/**
 * @author Adam Shorland
 */
class ReferenceRemover {

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
	 * @param Reference|string $reference Reference object or hash
	 * @param Claim|ClaimGuid|string $target Claim object or GUID
	 */
	public function set( $reference, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 