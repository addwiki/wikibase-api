<?php


namespace Wikibase\Api\Service;


use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use UnexpectedValueException;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Snak\Snak;

/**
 * @author Adam Shorland
 */
class ClaimCreator {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var DataValueSerializer
	 */
	private $dataValueSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param DataValueSerializer $dataValueSerializer
	 */
	public function __construct( MediawikiApi $api, DataValueSerializer $dataValueSerializer ) {
		$this->api = $api;
		$this->dataValueSerializer = $dataValueSerializer;
	}

	/**
	 * @since 0.2
	 *
	 * @param Snak $mainSnak
	 * @param EntityId|Entity|string $target
	 *
	 * @return bool
	 * @throws UnexpectedValueException
	 */
	public function create( Snak $mainSnak, $target ) {
		if( is_string( $target ) ) {
			$entityId = $target;
		} elseif ( $target instanceof EntityId ) {
			$entityId = $target->getSerialization();
		} elseif ( $target instanceof Entity ) {
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
				$params['value'] = $serializedDataValue['value'];
			} else {
				$params['value'] = json_encode( $serializedDataValue );
			}
		}

		$this->api->postAction( 'wbcreateclaim', $params );
		return true;
	}

} 