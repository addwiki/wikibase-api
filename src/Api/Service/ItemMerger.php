<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
use InvalidArgumentException;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @access private
 */
class ItemMerger {

	private WikibaseApi $api;

	public function __construct( WikibaseApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param Item|ItemId|string $from
	 * @param Item|ItemId|string $to
	 * @param EditInfo|null $editInfo
	 */
	public function merge( $from, $to, EditInfo $editInfo = null ): bool {
		$params = [
			'fromid' => $this->getIdFromInput( $from ),
			'toid' => $this->getIdFromInput( $to )
		];

		$this->api->postRequest( 'wbmergeitems', $params, $editInfo );
		return true;
	}

	/**
	 * @param Item|ItemId|string $input
	 *
	 * @throws InvalidArgumentException
	 * @return string|void the ItemId Serialization
	 */
	private function getIdFromInput( $input ) {
		if ( is_string( $input ) ) {
			return $input;
		} elseif ( $input instanceof ItemId ) {
			return $input->getSerialization();
		} elseif ( $input instanceof Item ) {
			return $input->getId()->getSerialization();
		} else {
			throw new InvalidArgumentException( 'Merge target should be either string, Item or ItemId' );
		}
	}

}
