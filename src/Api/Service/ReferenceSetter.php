<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use Serializers\Serializer;
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
class ReferenceSetter {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @var Serializer
	 */
	private $referenceSerializer;

	/**
	 * @param WikibaseApi $api
	 * @param Serializer $referenceSerializer
	 */
	public function __construct( WikibaseApi $api, Serializer $referenceSerializer ) {
		$this->api = $api;
		$this->referenceSerializer = $referenceSerializer;
	}

	/**
	 * @since 0.2
	 *
	 * @param Reference $reference new reference value
	 * @param Statement|StatementGuid|string $statement Statement object or GUID which has the reference
	 * @param Reference|string $targetReference target (old) reference of hash
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function set( Reference $reference, $statement, $targetReference = null, EditInfo $editInfo = null ) {
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

		$referenceSerialization = $this->referenceSerializer->serialize( $reference );

		$params = array(
			'statement' => $guid,
			'snaks' => json_encode( $referenceSerialization['snaks'] ),
			'snaks-order' => json_encode( $referenceSerialization['snaks-order'] ),
		);

		if( !is_null( $targetReference ) ) {
			if( $targetReference instanceof Reference ) {
				$targetReference = $reference->getHash();
			}
			if( !is_string( $targetReference ) ) {
				throw new UnexpectedValueException( 'Could not get reference hash from $targetReference' );
			}
			$params['reference'] = $targetReference;
		}

		$this->api->postRequest( 'wbsetreference', $params, $editInfo );
		return true;
	}

} 
