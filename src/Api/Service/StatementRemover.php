<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use UnexpectedValueException;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementGuid;

/**
 * @since 0.5
 *
 * @author Adam Shorland
 */
class StatementRemover {

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
	 * @param Statement|StatementGuid|string $statement Statement object or GUID
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function remove( $statement ) {
		if( is_string( $statement ) ) {
			$guid = $statement;
		} else if ( $statement instanceof StatementGuid ) {
			$guid = $statement->getSerialization();
		} else if ( $statement instanceof Statement ) {
			$guid = $statement->getGuid();
		} else {
			throw new UnexpectedValueException( 'Could not get statement guid from $statement' );
		}
		if( !is_string( $guid ) ) {
			throw new UnexpectedValueException( 'Unexpected statement guid got from $statement' );
		}

		$params = array(
			'claim' => $guid,
		);

		$params['token'] = $this->api->getToken();
		$this->api->postRequest( new SimpleRequest( 'wbremoveclaims', $params ) );
		return true;
	}

} 