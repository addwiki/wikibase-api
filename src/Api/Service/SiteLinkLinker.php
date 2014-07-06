<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\SiteLink;

/**
 * @author Adam Shorland
 */
class SiteLinkLinker {

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
	 * @param SiteLink $toSiteLink
	 * @param SiteLink $fromSiteLink
	 *
	 * @returns bool
	 */
	public function link ( SiteLink $toSiteLink, SiteLink $fromSiteLink ) {
		$params = array(
			'tosite' => $toSiteLink->getSiteId(),
			'totitle' => $toSiteLink->getPageName(),
			'fromsite' => $fromSiteLink->getSiteId(),
			'fromtitle' => $fromSiteLink->getPageName(),
		);

		$params['token'] = $this->api->getToken();
		$this->api->postAction( 'wblinktitles', $params );
		return true;
	}

} 