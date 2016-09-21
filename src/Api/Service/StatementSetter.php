<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\DataModel\EditInfo;
use Serializers\Serializer;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Statement\Statement;

/**
 * @access private
 *
 * @author Addshore
 */
class StatementSetter {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @var Serializer
	 */
	private $statementSerializer;

	/**
	 * @param WikibaseApi $api
	 * @param Serializer $statementSerializer
	 */
	public function __construct( WikibaseApi $api, Serializer $statementSerializer ) {
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
			'claim' => json_encode( $this->statementSerializer->serialize( $statement ) ),
		);

		$this->api->postRequest( 'wbsetclaim', $params, $editInfo );
		return true;
	}

} 
