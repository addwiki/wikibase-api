<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Wikibase\DataModel\Serializers\StatementSerializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @since 0.5
 *
 * @author Adam Shorland
 */
class StatementSetter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var StatementSerializer
	 */
	private $statementSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param StatementSerializer $statementSerializer
	 */
	public function __construct( MediawikiApi $api, StatementSerializer $statementSerializer ) {
		$this->api = $api;
		$this->statementSerializer = $statementSerializer;
	}

	/**
	 * @since 0.5
	 *
	 * @param Statement $statement
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 *
	 * @todo allow setting of indexes
	 */
	public function set( Statement $statement ) {
		if( $statement->getGuid() === null ) {
			throw new InvalidArgumentException( 'Can not set a statement that does not have a GUID' );
		}

		$params = array(
			'claim' => $this->statementSerializer->serialize( $statement ),
		);

		$params['token'] = $this->api->getToken();
		$this->api->postRequest( new SimpleRequest( 'wbsetclaim', $params ) );
		return true;
	}

} 