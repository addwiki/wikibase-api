<?php

namespace Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\PropertyLookup;
use Wikibase\DataModel\Services\Lookup\PropertyNotFoundException;

/**
 * @author Thomas Pellissier Tanon
 */
class PropertyApiLookup implements PropertyLookup {

	/**
	 * @var EntityLookup
	 */
	private $entityLookup;

	/**
	 * @param EntityLookup $entityLookup
	 */
	public function __construct( EntityLookup $entityLookup ) {
		$this->entityLookup = $entityLookup;
	}

	/**
	 * @see ItemLookup::getPropertyForId
	 */
	public function getPropertyForId( PropertyId $propertyId ) {
		$entity = $this->entityLookup->getEntity( $propertyId );

		if( $entity === null ) {
			throw new PropertyNotFoundException( $propertyId );
		}

		return $entity;
	}
}
