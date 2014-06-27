<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use UnexpectedValueException;
use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\SiteLink;

/**
 * @author Adam Shorland
 */
class SiteLinkSetter {

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
	 *
	 * @param SiteLink $siteLink
	 * @param EntityId|Entity|SiteLink $target
	 *
	 * @return bool
	 */
	public function set( SiteLink $siteLink, $target ) {
		$params = $this->getTargetParamsFromTarget(
			$this->getEntityIdFromTarget( $target )
		);

		$params['linksite'] = $siteLink->getSiteId();
		$params['linktitle'] = $siteLink->getPageName();

		$this->api->postAction( 'wblinktitles', $params );
		return true;
	}

	/**
	 * @param EntityId|Entity $target
	 *
	 * @throws UnexpectedValueException
	 * @return EntityId|SiteLink
	 *
	 * @todo Fix duplicated code
	 */
	private function getEntityIdFromTarget( $target ) {
		if( $target instanceof EntityId || $target instanceof SiteLink ) {
			return $target;
		} elseif ( $target instanceof Entity ) {
			$target = $target->getId();
			if( !is_null( $target ) ) {
				return $target;
			} else {
				throw new UnexpectedValueException( '$target Entity object needs to have an Id set' );
			}
		} else {
			throw new UnexpectedValueException( '$target needs to be an EntityId, Entity or SiteLink' );
		}
	}

	/**
	 * @param EntityId|SiteLink $target
	 *
	 * @throws UnexpectedValueException
	 * @return array
	 *
	 * @todo Fix duplicated code
	 */
	private function getTargetParamsFromTarget( $target ) {
		if( $target instanceof EntityId ) {
			return array( 'id' => $target->getSerialization() );
		} elseif( $target instanceof SiteLink ) {
			return array(
				'site' => $target->getSiteId(),
				'title' => $target->getPageName(),
			);
		} else {
			throw new UnexpectedValueException( '$target needs to be an EntityId or SiteLink' );
		}
	}
} 