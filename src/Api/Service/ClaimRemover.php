<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use UnexpectedValueException;
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
	 *
	 * @param Claim|ClaimGuid|string $claim Claim object or GUID
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function remove( $claim ) {
		if( is_string( $claim ) ) {
			$guid = $claim;
		} else if ( $claim instanceof ClaimGuid ) {
			$guid = $claim->getSerialization();
		} else if ( $claim instanceof Claim ) {
			$guid = $claim->getGuid();
		} else {
			throw new UnexpectedValueException( 'Could not get claim guid from $claim' );
		}
		if( !is_string( $guid ) ) {
			throw new UnexpectedValueException( 'Unexpected claim guid got from $claim' );
		}

		$params = array(
			'claim' => $guid,
		);

		$params['token'] = $this->api->getToken();
		$this->api->postRequest( new SimpleRequest( 'wbremoveclaims', $params ) );
		return true;
	}

} 