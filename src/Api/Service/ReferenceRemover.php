<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
use UnexpectedValueException;
use Wikibase\DataModel\Reference;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementGuid;

/**
 * @access private
 */
class ReferenceRemover {

	private WikibaseApi $api;

	public function __construct( WikibaseApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param Reference|string $reference Reference object or hash
	 * @param Statement|StatementGuid|string $target Statement object or GUID
	 * @param EditInfo|null $editInfo
	 *
	 * @throws UnexpectedValueException
	 */
	public function set( $reference, $target, EditInfo $editInfo = null ): bool {
		if ( $reference instanceof Reference ) {
			$reference = $reference->getHash();
		}

		if ( !is_string( $reference ) ) {
			throw new UnexpectedValueException( 'Could not get reference hash from $reference' );
		}

		if ( is_string( $target ) ) {
			$guid = $target;
		} elseif ( $target instanceof StatementGuid ) {
			$guid = $target->getSerialization();
		} elseif ( $target instanceof Statement ) {
			$guid = $target->getGuid();
		} else {
			throw new UnexpectedValueException( 'Could not get statement guid from $target' );
		}

		if ( !is_string( $guid ) ) {
			throw new UnexpectedValueException( 'Unexpected statement guid got from $target' );
		}

		$params = [
			'statement' => $guid,
			'references' => $reference,
		];

		$this->api->postRequest( 'wbremovereferences', $params, $editInfo );
		return true;
	}

}
