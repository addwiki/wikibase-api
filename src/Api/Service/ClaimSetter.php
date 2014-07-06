<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Serializers\ClaimSerializer;

/**
 * @author Adam Shorland
 */
class ClaimSetter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var ClaimSerializer
	 */
	private $claimSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param ClaimSerializer $claimSerializer
	 */
	public function __construct( MediawikiApi $api, ClaimSerializer $claimSerializer ) {
		$this->api = $api;
		$this->claimSerializer = $claimSerializer;
	}

	/**
	 * @since 0.2
	 *
	 * @param Claim $claim
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 *
	 * @todo allow setting of indexes
	 */
	public function set( Claim $claim ) {
		if( $claim->getGuid() === null ) {
			throw new InvalidArgumentException( 'Can not set a claim that does not have a GUID' );
		}

		$params = array(
			'claim' => $this->claimSerializer->serialize( $claim ),
		);

		$params['token'] = $this->api->getToken();
		$this->api->postAction( 'wbsetclaim', $params );
		return true;
	}

} 