<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use UnexpectedValueException;
use Wikibase\DataModel\Reference;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementGuid;

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
	 * @param Statement|StatementGuid|string $target Statement object or GUID
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
			$guid = $target;
		} else if ( $target instanceof StatementGuid ) {
			$guid = $target->getSerialization();
		} else if ( $target instanceof Statement ) {
			$guid = $target->getGuid();
		} else {
			throw new UnexpectedValueException( 'Could not get statement guid from $target' );
		}
		if( !is_string( $guid ) ) {
			throw new UnexpectedValueException( 'Unexpected statement guid got from $target' );
		}

		$params = array(
			'statement' => $guid,
			'references' => $reference,
		);

		$params['token'] = $this->api->getToken();
		$this->api->postRequest( new SimpleRequest( 'wbremovereferences', $params ) );
		return true;
	}

} 