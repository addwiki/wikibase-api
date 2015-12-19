<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use UnexpectedValueException;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Reference;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementGuid;

/**
 * @access private
 *
 * @author Addshore
 */
class ReferenceRemover {

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
	 * @param Reference|string $reference Reference object or hash
	 * @param Statement|StatementGuid|string $target Statement object or GUID
	 * @param EditInfo|null $editInfo
	 *
	 * @throws UnexpectedValueException
	 * @return bool
	 */
	public function set( $reference, $target, EditInfo $editInfo = null ) {
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

		$this->api->postRequest( 'wbremovereferences', $params, $editInfo );
		return true;
	}

} 