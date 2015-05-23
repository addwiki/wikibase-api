<?php

namespace Wikibase\Api;

use Wikibase\Api\Service\RevisionGetter;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\ItemLookup;
use Wikibase\DataModel\Entity\ItemNotFoundException;

/**
 * @author Thomas Pellissier-Tanon
 */
class ItemApiLookup implements ItemLookup {

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
	 * @see ItemLookup::getItemForId
	 */
	public function getItemForId( ItemId $itemId ) {
		$revision = $this->revisionGetter->getFromId( $itemId );

		if( !$revision ) {
			throw new ItemNotFoundException( $itemId );
		}

		return $revision->getContent()->getData();
	}
}
