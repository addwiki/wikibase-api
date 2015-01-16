<?php


namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Deserializers\ClaimDeserializer;

/**
 * @author Adam Shorland
 */
class ClaimGetter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var ClaimDeserializer
	 */
	private $claimDeserializer;

	/**
	 * @param MediawikiApi $api
	 * @param ClaimDeserializer $claimDeserializer
	 */
	public function __construct( MediawikiApi $api, ClaimDeserializer $claimDeserializer ) {
		$this->api = $api;
		$this->claimDeserializer = $claimDeserializer;
	}

	/**
	 * @param string $guid
	 *
	 * @return Claim
	 */
	public function getFromGuid( $guid ) {
		$params = array(
			'claim' => $guid,
		);

		$result = $this->api->getAction( 'wbgetclaims', $params );

		$claimSerialization = array_shift( array_shift( $result['claims'] ) );

		return $this->claimDeserializer->deserialize( $claimSerialization );
	}

} 