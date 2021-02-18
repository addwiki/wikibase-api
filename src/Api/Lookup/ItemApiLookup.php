<?php

namespace Addwiki\Wikibase\Api\Lookup;

use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\ItemLookup;

/**
 * @access private
 */
class ItemApiLookup implements ItemLookup {

	private EntityLookup $entityLookup;

	public function __construct( EntityLookup $entityLookup ) {
		$this->entityLookup = $entityLookup;
	}

	/**
	 * @see ItemLookup::getItemForId
	 * @return EntityDocument|null
	 */
	public function getItemForId( ItemId $itemId ) {
		return $this->entityLookup->getEntity( $itemId );
	}
}
