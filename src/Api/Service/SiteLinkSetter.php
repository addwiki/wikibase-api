<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\SiteLink;

/**
 * @author Adam Shorland
 */
class SiteLinkSetter {

	/**
	 * @since 0.2
	 * @param SiteLink $label
	 * @param EntityId|Entity $target
	 */
	public function setSiteLink( SiteLink $siteLink, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}
} 