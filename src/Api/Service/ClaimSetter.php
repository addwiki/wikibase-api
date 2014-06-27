<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;

/**
 * @author Adam Shorland
 */
class ClaimSetter {

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
	 * @param Claim $claim
	 */
	public function set( Claim $claim ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 