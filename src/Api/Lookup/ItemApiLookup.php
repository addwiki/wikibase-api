<?php

namespace Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\ItemLookup;

/**
 * @access private
 *
 * @author Thomas Pellissier Tanon
 * @author Addshore
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

		return $entity;
	}
}
