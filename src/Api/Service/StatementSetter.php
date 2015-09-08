<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\EditInfo;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Serializers\StatementSerializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @since 0.5
 *
 * @author Adam Shorland
 */
class StatementSetter {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @var StatementSerializer
	 */
	private $statementSerializer;

	/**
	 * @param WikibaseApi $api
	 * @param StatementSerializer $statementSerializer
	 */
	public function __construct( WikibaseApi $api, StatementSerializer $statementSerializer ) {
		$this->api = $api;
		$this->statementSerializer = $statementSerializer;
	}

	/**
	 * @since 0.5
	 *
	 * @param Statement $statement
	 * @param EditInfo|null $editInfo
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 *
	 * @todo allow setting of indexes
	 */
	public function set( Statement $statement, EditInfo $editInfo = null ) {
		if( $statement->getGuid() === null ) {
			throw new InvalidArgumentException( 'Can not set a statement that does not have a GUID' );
		}

		$params = array(
			'claim' => $this->statementSerializer->serialize( $statement ),
		);

		$this->api->postRequest( 'wbsetclaim', $params, $editInfo );
		return true;
	}

} 