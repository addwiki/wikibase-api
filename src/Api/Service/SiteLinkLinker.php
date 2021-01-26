<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\SiteLink;

/**
 * @access private
 *
 * @author Addshore
 */
class SiteLinkLinker {

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
	 * @param SiteLink $toSiteLink
	 * @param SiteLink $fromSiteLink
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 */
	public function link( SiteLink $toSiteLink, SiteLink $fromSiteLink, EditInfo $editInfo = null ) {
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
