<?php


namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @since 0.5
 * @author Adam Shorland
 */
class BadgeIdsGetter {

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
	 * @since 0.5
	 * @return ItemId[]
	 */
	public function get() {
		$result = $this->api->getAction( 'wbavailablebadges' );
		$ids = array();
		foreach( $result['badges'] as $badgeIdString ) {
			$ids[] = new ItemId( $badgeIdString );
		}
		return $ids;
	}

} 