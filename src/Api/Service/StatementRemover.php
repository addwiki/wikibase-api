<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use UnexpectedValueException;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementGuid;

/**
 * @access private
 *
 * @author Addshore
 */
class StatementRemover {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @param WikibaseApi $api
	 */
	public function __construct( WikibaseApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.2
	 *
	 * @param Statement|StatementGuid|string $statement Statement object or GUID
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function remove( $statement, EditInfo $editInfo = null ) {
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

		$this->api->postRequest( 'wbremoveclaims', $params, $editInfo );
		return true;
	}

} 