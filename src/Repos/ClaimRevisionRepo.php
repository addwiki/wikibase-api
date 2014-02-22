<?php

namespace Wikibase\Api\Repos;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\ClaimGuid;

class ClaimRevisionRepo {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param string|ClaimGuid $guid
	 * @returns Claim
	 */
	public function getFromId( $guid ) {
		//TODO
	}

} 