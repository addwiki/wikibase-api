<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
use Serializers\Serializer;
use UnexpectedValueException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Snak\Snak;

/**
 * @access private
 */
class StatementCreator {

	private WikibaseApi $api;

	private Serializer $dataValueSerializer;

	public function __construct( WikibaseApi $api, Serializer $dataValueSerializer ) {
		$this->api = $api;
		$this->dataValueSerializer = $dataValueSerializer;
	}

	/**
	 * @param EntityId|Item|Property|string $target
	 * @param EditInfo|null $editInfo
	 *
	 * @return string the GUID of the claim
	 * @throws UnexpectedValueException
	 */
	public function create( Snak $mainSnak, $target, EditInfo $editInfo = null ): string {
		if ( is_string( $target ) ) {
			$entityId = $target;
		} elseif ( $target instanceof EntityId ) {
			$entityId = $target->getSerialization();
		} elseif ( $target instanceof Item || $target instanceof Property ) {
			$entityId = $target->getId()->getSerialization();
		} else {
			throw new UnexpectedValueException( '$target needs to be an EntityId, Entity or string' );
		}

		$params = [
			'entity' => $entityId,
			'snaktype' => $mainSnak->getType(),
			'property' => $mainSnak->getPropertyId()->getSerialization(),
		];
		if ( $mainSnak instanceof PropertyValueSnak ) {
			$serializedDataValue = $this->dataValueSerializer->serialize( $mainSnak->getDataValue() );
			$params['value'] = json_encode( $serializedDataValue['value'] );
		}

		$result = $this->api->postRequest( 'wbcreateclaim', $params, $editInfo );
		return $result['claim']['id'];
	}

}
