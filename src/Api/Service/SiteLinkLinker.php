<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\SiteLink;

/**
 * @access private
 *
 * @author Addshore
 */
class SiteLinkLinker {

	private WikibaseApi $api;

	/**
	 * @param WikibaseApi $api
	 */
	public function __construct( WikibaseApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.2
	 * @param SiteLink $toSiteLink
	 * @param SiteLink $fromSiteLink
	 * @param EditInfo|null $editInfo
	 */
	public function link( SiteLink $toSiteLink, SiteLink $fromSiteLink, EditInfo $editInfo = null ): bool {
		$params = [
			'tosite' => $toSiteLink->getSiteId(),
			'totitle' => $toSiteLink->getPageName(),
			'fromsite' => $fromSiteLink->getSiteId(),
			'fromtitle' => $fromSiteLink->getPageName(),
		];

		$this->api->postRequest( 'wblinktitles', $params, $editInfo );
		return true;
	}

}
