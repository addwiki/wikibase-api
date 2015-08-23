<?php

namespace Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\ItemLookup;
use Wikibase\DataModel\Services\Lookup\ItemNotFoundException;

/**
 * @author Thomas Pellissier Tanon
 */
class ItemApiLookup implements ItemLookup {

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
	 * @see ItemLookup::getItemForId
	 */
	public function getItemForId( ItemId $itemId ) {
		$entity = $this->entityLookup->getEntity( $itemId );

		if( $entity === null ) {
			throw new ItemNotFoundException( $itemId );
		}

		return $entity;
	}
}
