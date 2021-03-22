<?php

namespace Addwiki\Wikibase\Api\Lookup;

use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Wikibase\Api\Service\RevisionGetter;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;

/**
 * @access private
 */
class EntityApiLookup implements EntityLookup {

	private RevisionGetter $revisionGetter;

	public function __construct( RevisionGetter $revisionGetter ) {
		$this->revisionGetter = $revisionGetter;
	}

	/**
	 * @see EntityLookup::getEntity
	 * @return null|mixed
	 */
	public function getEntity( EntityId $entityId ) {
		$revision = $this->revisionGetter->getFromId( $entityId );

		if ( !$revision instanceof Revision ) {
			return null;
		}

		return $revision->getContent()->getData();
	}

	/**
	 * @see EntityLookup::hasEntity
	 * @return bool
	 */
	public function hasEntity( EntityId $entityId ) {
		$revision = $this->revisionGetter->getFromId( $entityId );
		return (bool)$revision;
	}
}
