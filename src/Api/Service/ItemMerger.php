<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\DataModel\EditInfo;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @access private
 *
 * @author Addshore
 */
class ItemMerger {

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
	 * @param Item|ItemId|string $from
	 * @param Item|ItemId|string $to
	 * @param EditInfo|null $editInfo
	 *
	 * @returns bool
	 */
	public function merge( $from, $to, EditInfo $editInfo = null ) {
		$params = array(
			'fromid' => $this->getIdFromInput( $from ),
			'toid' => $this->getIdFromInput( $to )
		);

		$this->api->postRequest( 'wbmergeitems', $params, $editInfo );
		return true;
	}

	/**
	 * @param Item|ItemId|string $input
	 *
	 * @throws InvalidArgumentException
	 * @return string the ItemId Serialization
	 */
	private function getIdFromInput( $input ) {
		if( is_string( $input ) ) {
			return $input;
		} elseif( $input instanceof ItemId ) {
			return $input->getSerialization();
		} elseif( $input instanceof Item ) {
			return $input->getId()->getSerialization();
		} else {
			throw new InvalidArgumentException( 'Merge target should be either string, Item or ItemId' );
		}
	}

} 