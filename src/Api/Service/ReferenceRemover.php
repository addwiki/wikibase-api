<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use UnexpectedValueException;
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
	 *
	 * @param Reference|string $reference Reference object or hash
	 * @param Claim|ClaimGuid|string $target Claim object or GUID
	 *
	 * @throws UnexpectedValueException
	 * @return bool
	 */
	public function set( $reference, $target ) {
		if( $reference instanceof Reference ) {
			$reference = $reference->getHash();
		}
		if( !is_string( $reference ) ) {
			throw new UnexpectedValueException( 'Could not get reference hash from $reference' );
		}

		if( is_string( $target ) ) {
			$claimGuid = $target;
		} else if ( $target instanceof ClaimGuid ) {
			$claimGuid = $target->getSerialization();
		} else if ( $target instanceof Claim ) {
			$claimGuid = $target->getGuid();
		} else {
			throw new UnexpectedValueException( 'Could not get claim guid from $target' );
		}
		if( !is_string( $claimGuid ) ) {
			throw new UnexpectedValueException( 'Unexpected claim guid got from $target' );
		}

		$params = array(
			'statement' => $claimGuid,
			'references' => $reference,
		);

		$params['token'] = $this->api->getToken();
		$this->api->postAction( 'wbremovereferences', $params );
		return true;
	}

} 