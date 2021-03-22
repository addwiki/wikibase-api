<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
use InvalidArgumentException;
use Serializers\Serializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @access private
 */
class StatementSetter {

	private WikibaseApi $api;

	private Serializer $statementSerializer;

	public function __construct( WikibaseApi $api, Serializer $statementSerializer ) {
		$this->api = $api;
		$this->statementSerializer = $statementSerializer;
	}

	/**
	 * @param EditInfo|null $editInfo
	 *
	 * @throws InvalidArgumentException
	 * @todo allow setting of indexes
	 */
	public function set( Statement $statement, EditInfo $editInfo = null ): bool {
		if ( $statement->getGuid() === null ) {
			throw new InvalidArgumentException( 'Can not set a statement that does not have a GUID' );
		}

		$params = [
			'claim' => json_encode( $this->statementSerializer->serialize( $statement ) ),
		];

		$this->api->postRequest( 'wbsetclaim', $params, $editInfo );
		return true;
	}

}
