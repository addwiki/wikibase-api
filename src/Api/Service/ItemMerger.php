<?php

namespace Wikibase\Api\Service;

use InvalidArgumentException;
use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @author Adam Shorland
 */
class ItemMerger {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.2
	 * @param Item|ItemId|string $from
	 * @param Item|ItemId|string $to
	 *
	 * @returns bool
	 */
	public function merge( $from, $to ) {
		$params = array(
			'fromid' => $this->getIdFromInput( $from ),
			'toid' => $this->getIdFromInput( $to ),
			'token' => $this->api->getToken(),
		);

		$params['token'] = $this->api->getToken();
		$this->api->postAction( 'wbmergeitems', $params );
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