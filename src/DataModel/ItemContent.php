<?php

namespace Wikibase\DataModel;

use Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Item;

/**
 * @author Addshore
 */
class ItemContent extends Content {

	const MODEL = 'wikibase-item';

	/**
	 * @param Item $item
	 */
	public function __construct( Item $item ) {
		parent::__construct( $item, self::MODEL );
	}

	/**
	 * @see Content::getData
	 * @return Item
	 */
	public function getData() {
		return parent::getData();
	}
}