<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @access private
 *
 * @author Addshore
 */
class BadgeIdsGetter {

	private MediawikiApi $api;

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
	public function get(): array {
		$result = $this->api->getRequest( new SimpleRequest( 'wbavailablebadges' ) );
		$ids = [];
		foreach ( $result['badges'] as $badgeIdString ) {
			$ids[] = new ItemId( $badgeIdString );
		}
		return $ids;
	}

}
