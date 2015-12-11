<?php

namespace Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\PropertyLookup;

/**
 * @access private
 *
 * @author Thomas Pellissier Tanon
 * @author Addshore
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

		return $entity;
	}
}
