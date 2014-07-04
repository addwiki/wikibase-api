<?php

namespace Wikibase\Api\Service;

use DataValues\Serializers\DataValueSerializer;
use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Serializers\ClaimSerializer;
use Wikibase\DataModel\Serializers\ReferenceSerializer;
use Wikibase\DataModel\Serializers\ReferencesSerializer;
use Wikibase\DataModel\Serializers\SnakSerializer;
use Wikibase\DataModel\Serializers\SnaksSerializer;

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

		//TODO inject me
		$snakSerializer = new SnakSerializer( new DataValueSerializer() );
		//TODO inject me
		$claimSerializer = new ClaimSerializer(
			$snakSerializer,
			new SnaksSerializer( $snakSerializer ),
			new ReferencesSerializer( new ReferenceSerializer( $snakSerializer ) )
		);

		$params = array(
			'claim' => $claimSerializer->serialize( $claim ),
		);

		$this->api->postAction( 'wbsetclaim', $params );
		return true;
	}

} 