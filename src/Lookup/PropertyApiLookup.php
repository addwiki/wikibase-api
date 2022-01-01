<?php

namespace Addwiki\Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\PropertyLookup;

/**
 * @access private
 */
class PropertyApiLookup implements PropertyLookup {

	private EntityLookup $entityLookup;

	public function __construct( EntityLookup $entityLookup ) {
		$this->entityLookup = $entityLookup;
	}

	/**
	 * @see ItemLookup::getPropertyForId
	 * @return EntityDocument|null
	 */
	public function getPropertyForId( PropertyId $propertyId ) {
		return $this->entityLookup->getEntity( $propertyId );
	}
}
