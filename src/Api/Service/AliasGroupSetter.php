<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use UnexpectedValueException;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\SiteLink;
use Wikibase\DataModel\Term\AliasGroup;

/**
 * @access private
 *
 * @author Addshore
 */
class AliasGroupSetter {

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
	 *
	 * @param AliasGroup $aliasGroup
	 * @param EntityId|Item|Property|SiteLink $target
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 */
	public function set( AliasGroup $aliasGroup, $target, EditInfo $editInfo = null ) {
		$this->throwExceptionsOnBadTarget( $target );

		$params = $this->getTargetParamsFromTarget(
			$this->getEntityIdentifierFromTarget( $target )
		);

		$params['language'] = $aliasGroup->getLanguageCode();
		$params['set'] = implode( '|', $aliasGroup->getAliases() );

		$this->api->postRequest( 'wbsetaliases', $params, $editInfo );
		return true;
	}

	/**
	 * @param mixed $target
	 *
	 * @throws UnexpectedValueException
	 *
	 * @todo Fix duplicated code
	 */
	private function throwExceptionsOnBadTarget( $target ) {
		if( !$target instanceof EntityId && !$target instanceof Item && !$target instanceof Property && ! $target instanceof SiteLink ) {
			throw new UnexpectedValueException( '$target needs to be an EntityId, Item, Property or SiteLink' );
		}
		if( ( $target instanceof Item || $target instanceof Property ) && is_null( $target->getId() ) ) {
			throw new UnexpectedValueException( '$target Entity object needs to have an Id set' );
		}
	}

	/**
	 * @param EntityId|Item|Property $target
	 *
	 * @throws UnexpectedValueException
	 * @return EntityId|SiteLink
	 *
	 * @todo Fix duplicated code
	 */
	private function getEntityIdentifierFromTarget( $target ) {
		if ( $target instanceof Item || $target instanceof Property ) {
			return $target->getId();
		} else {
			return $target;
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