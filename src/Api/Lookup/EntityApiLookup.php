<?php

namespace Wikibase\Api\Lookup;

use Wikibase\Api\Service\RevisionGetter;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;

/**
 * @author Addshore
 *
 * @access private
 */
class EntityApiLookup implements EntityLookup {

	/**
	 * @var RevisionGetter
	 */
	private $revisionGetter;

	/**
	 * @param RevisionGetter $revisionGetter
	 */
	public function __construct( RevisionGetter $revisionGetter ) {
		$this->revisionGetter = $revisionGetter;
	}

	/**
	 * @see EntityLookup::getEntity
	 */
	public function getEntity( EntityId $entityId ) {
		$revision = $this->revisionGetter->getFromId( $entityId );

		if( !$revision ) {
			return null;
		}

		return $revision->getContent()->getData();
	}

	/**
	 * @see EntityLookup::hasEntity
	 */
	public function hasEntity( EntityId $entityId ) {
		$revision = $this->revisionGetter->getFromId( $entityId );

		if( !$revision ) {
			return false;
		}

		return true;
	}
}
