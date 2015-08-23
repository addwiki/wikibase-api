<?php

namespace Wikibase\Api;

use Wikibase\Api\Service\RevisionGetter;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\PropertyLookup;
use Wikibase\DataModel\Services\Lookup\PropertyNotFoundException;

/**
 * @author Thomas Pellissier Tanon
 */
class PropertyApiLookup implements PropertyLookup {

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
	 * @see PropertyLookup::getPropertyForId
	 */
	public function getPropertyForId( PropertyId $itemId ) {
		$revision = $this->revisionGetter->getFromId( $itemId );

		if( !$revision ) {
			throw new PropertyNotFoundException( $itemId );
		}

		return $revision->getContent()->getData();
	}
}
