<?php


namespace Wikibase\Api\Service;


use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\EditInfo;
use UnexpectedValueException;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Snak\Snak;

/**
 * @access private
 *
 * @author Adam Shorland
 */
class StatementCreator {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @var Deserializer
	 */
	private $dataValueSerializer;

	/**
	 * @param WikibaseApi $api
	 * @param Deserializer $dataValueSerializer
	 */
	public function __construct( WikibaseApi $api, Deserializer $dataValueSerializer ) {
		$this->api = $api;
		$this->dataValueSerializer = $dataValueSerializer;
	}

	/**
	 * @since 0.2
	 *
	 * @param Snak $mainSnak
	 * @param EntityId|Item|Property|string $target
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function create( Snak $mainSnak, $target, EditInfo $editInfo = null ) {
		if( is_string( $target ) ) {
			$entityId = $target;
		} elseif ( $target instanceof EntityId ) {
			$entityId = $target->getSerialization();
		} elseif ( $target instanceof Item || $target instanceof Property ) {
			$entityId = $target->getId()->getSerialization();
		} else {
			throw new UnexpectedValueException( '$target needs to be an EntityId, Entity or string' );
		}

		$params = array(
			'entity' => $entityId,
			'snaktype' => $mainSnak->getType(),
			'property' => $mainSnak->getPropertyId()->getSerialization(),
		);
		if( $mainSnak instanceof PropertyValueSnak ) {
			$serializedDataValue = $this->dataValueSerializer->serialize( $mainSnak->getDataValue() );
			if( $serializedDataValue['type'] === 'string' ) {
				$params['value'] = json_encode( $serializedDataValue['value'] );
			} else {
				$params['value'] = json_encode( $serializedDataValue );
			}
		}

		$this->api->postRequest( 'wbcreateclaim', $params, $editInfo );
		return true;
	}

} 